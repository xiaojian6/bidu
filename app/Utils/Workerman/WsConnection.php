<?php

namespace App\Utils\Workerman;

use Workerman\Connection\AsyncTcpConnection;
use Workerman\Lib\Timer;
use App\Models\{CurrencyMatch, CurrencyQuotation, MarketHour};
use App\Jobs\{EsearchMarket, LeverUpdate, SendMarket};

class WsConnection
{
    protected $server_address = 'ws://api.huobi.pro:443/ws';
    // protected $server_address = 'ws://api.huobi.br.com:443/ws'; //ws国内开发调试
    protected $server_ping_freq = 10; //服务器ping检测周期,单位秒
    protected $server_time_out = 2; //服务器响应超时
    protected $send_freq = 1; //写入和发送数据的周期，单位秒

    protected $worker_id;

    protected $events = [
        'onConnect',
        'onClose',
        'onMessage',
        'onError',
        'onBufferFull',
        'onBufferDrain',
    ];

    protected static $marketKlineData = [];
    protected static $marketDepthData = []; //盘口深度数据
    protected static $matchTradeData = []; //撮合交易全站交易

    protected $connection;

    protected $timer;

    protected $pingTimer;

    protected $sendKlineTimer;

    protected $sendDepthTimer;

    protected $sendMatchTradeTimer;

    protected $handleTimer;

    protected $subscribed = [];

    protected $topicTemplate = [
        'sub' => [
            'market_kline' => 'market.$symbol.kline.$period', //K线
            'market_detail' => 'market.$symbol.detail',
            'market_depth' => 'market.$symbol.depth.$type', //盘口深度
            'market_trade' => 'market.$symbol.trade.detail', //成交的交易
        ],
    ];

    public function __construct($worker_id)
    {
        $this->worker_id = $worker_id;
        AsyncTcpConnection::$defaultMaxPackageSize = 1048576000;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * 绑定所有事件到连接
     *
     * @return void
     */
    protected function bindEvent()
    {
        foreach ($this->events as $key => $event) {
            if (method_exists($this, $event)) {
                $this->connection && $this->connection->$event = [$this, $event];
                //echo '绑定' . $event . '事件成功' . PHP_EOL;
            }
        }
    }

    /**
     * 解除连接所有绑定事件
     *
     * @return void
     */
    protected function unBindEvent()
    {
        foreach ($this->events as $key => $event) {
            if (method_exists($this, $event)) {
                $this->connection && $this->connection->$event = null;
                //echo '解绑' . $event . '事件成功' . PHP_EOL;
            }
        }
    }

    public function getSubscribed($topic = null)
    {
        if (is_null($topic)) {
            return $this->subscribed;
        }
        return $this->subscribed[$topic] ?? null;
    }

    protected function setSubscribed($topic, $value)
    {
        $this->subscribed[$topic] = $value;
    }

    protected function delSubscribed($topic)
    {
        unset($this->subscribed[$topic]);
    }

    public function connect()
    {
        $this->connection = new AsyncTcpConnection($this->server_address);
        $this->bindEvent();
        $this->connection->transport = 'ssl';
        $this->connection->connect();
    }

    public function onConnect($con)
    {
        //连接成功后定期发送ping数据包检测服务器是否在线
        $this->timer = Timer::add($this->server_ping_freq, [$this, 'ping'], [$this->connection], true);
        //添加订阅事件代码
        $this->subscribe($con);
        if ($this->worker_id < 8) {
            $this->sendKlineTimer = Timer::add($this->send_freq, [$this, 'writeMarketKline'], [], true);
        } elseif ($this->worker_id == 8) {
            $this->depthTimer = Timer::add($this->send_freq, [$this, 'sendDepthData'], [], true);
            $this->sendMatchTradeTimer = Timer::add($this->send_freq, [$this, 'sendMatchTradeData'], [], true);
        }
        if ($this->worker_id == 5) {
            $this->handleTimer = Timer::add($this->send_freq, [self::class, 'sendLeverHandle'], [], true);
        }
    }

    public function onClose($con)
    {
        echo $this->server_address . '连接关闭' . PHP_EOL;
        $path = base_path() . '/storage/logs/wss/';
        $filename = date('Ymd') . '.log';
        file_exists($path) || @mkdir($path);
        error_log(date('Y-m-d H:i:s') . ' ' . $this->server_address . '连接关闭' . PHP_EOL, 3, $path . $filename);
        //解除事件
        $this->timer && Timer::del($this->timer);
        $this->sendKlineTimer && Timer::del($this->sendKlineTimer);
        $this->pingTimer && Timer::del($this->pingTimer);
        $this->depthTimer && Timer::del($this->depthTimer);
        $this->handleTimer && Timer::del($this->handleTimer);

        $this->unBindEvent();
        unset($this->connection);
        $this->connection = null;
        $this->subscribed = null; //清空订阅
        echo '尝试重新连接' . PHP_EOL;
        $this->connect();
    }

    public function close($msg)
    {
        $path = base_path() . '/storage/logs/wss/';
        $filename = date('Ymd') . '.log';
        file_exists($path) || @mkdir($path);
        error_log(date('Y-m-d H:i:s') . ' ' . $msg, 3, $path . $filename);
        $this->connection->destroy();
    }

    protected function makeSubscribeTopic($topic_template, $param)
    {
        $need_param = [];
        $match_count = preg_match_all('/\$([a-zA-Z_]\w*)/', $topic_template, $need_param);
        if ($match_count > 0 && count(reset($need_param)) > count($param)) {
            throw new \Exception('所需参数不匹配');
        }
        $diff = array_diff(next($need_param), array_keys($param));
        if (count($diff) > 0) {
            throw new \Exception('topic:' . $topic_template . '缺少参数：' . implode(',', $diff));
        }
        return preg_replace_callback('/\$([a-zA-Z_]\w*)/', function ($matches) use ($param) {
            extract($param);
            $value = $matches[1];
            return $$value ?? '';
        }, $topic_template);
    }

    public function onBufferFull()
    {
        echo 'buffer is full' . PHP_EOL;
    }

    protected function subscribe($con)
    {
        $periods = ['1min', '5min', '15min', '30min', '60min', '1day', '1mon', '1week']; //['1day', '1min'];
        if ($this->worker_id < 8) {
            $value = $periods[$this->worker_id];
            // echo '进程'. $this->worker_id . '开始订阅' . $value . '数据' . PHP_EOL;
            $this->subscribeKline($con, $value); //订阅k线行情
        } else {
            if ($this->worker_id == 8) {
                $this->subscribeMarketDepth($con); //订阅盘口数据
                $this->subscribeMarketTrade($con); //订阅全站交易数据
            }
        }
    }

    //订阅回调
    protected function onSubscribe($data)
    {
        if ($data->status == 'ok') {
            echo date('Y-m-d H:i:s ') . $data->subbed . '订阅成功' . PHP_EOL;
        } else {
            echo '订阅失败:' . $data->{'err-msg'} . PHP_EOL;
        }
    }

    //订阅K线行情
    protected function subscribeKline($con, $period)
    {
        $currency_match = CurrencyMatch::getHuobiMatchs();
        foreach ($currency_match as $key => $value) {
            $param = [
                'symbol' => $value->match_name,
                'period' => $period,
            ];
            $topic = $this->makeSubscribeTopic($this->topicTemplate['sub']['market_kline'], $param);
            $sub_data = json_encode([
                'sub' => $topic,
                'id' => $topic,
                //'freq-ms' => 5000, //推送频率，实测只能是0和5000，与官网文档不符
            ]);
            //未订阅过的才能订阅
            $subscribed_data = $this->getSubscribed($topic);
            $match_data = $subscribed_data['match'] ?? [];
            $match_data[] = $value;
            $this->setSubscribed($topic, [
                'callback' => 'onMarketKline',
                'match' => $match_data,
            ]);
            if (is_null($subscribed_data)) {
                $con->send($sub_data);
            }
        }
    }

    protected function onMarketKline($con, $data, $match)
    {   
        $topic = $data->ch;
        $msg = date('Y-m-d H:i:s') . ' 进程' . $this->worker_id . '接收' . $topic  . '行情' . PHP_EOL;
        list($name, $symbol, $detail_name, $period) = explode('.', $topic);
        $subscribed_data = $this->getSubscribed($topic);
        $currency_matches = $subscribed_data['match'];
        foreach ($currency_matches as $key => $currency_match) {
            $tick = $data->tick;
            $market_data = [
                'id' => $tick->id,
                'period' => $period,
                'match_id' => $currency_match->id,
                'base-currency' => $currency_match->currency_name,
                'quote-currency' => $currency_match->legal_name,
                'open' => sctonum($tick->open),
                'close' => sctonum($tick->close),
                'high' => sctonum($tick->high),
                'low' => sctonum($tick->low),
                'vol' => sctonum($tick->vol),
                'amount' => sctonum($tick->amount),
            ];
            $kline_data = [
                'type' => 'kline',
                'period' => $period,
                'match_id' => $currency_match->id,
                'currency_id' => $currency_match->currency_id,
                'currency_name' => $currency_match->currency_name,
                'legal_id' => $currency_match->legal_id,
                'legal_name' => $currency_match->legal_name,
                'open' => sctonum($tick->open),
                'close' => sctonum($tick->close),
                'high' => sctonum($tick->high),
                'low' => sctonum($tick->low),
                'symbol' => $currency_match->currency_name . '/' . $currency_match->legal_name,
                'volume' => sctonum($tick->amount),
                'time' => $tick->id * 1000,
            ];
            $symbol_key = $currency_match->currency_name . '.' . $currency_match->legal_name;
            self::$marketKlineData[$period][$symbol_key] = [
                'market_data' => $market_data,
                'kline_data' => $kline_data,
            ];
            if ($period == '1day') {
                //推送币种的日行情(带涨副)
                $change = $this->calcIncreasePair($kline_data);
                bc_comp($change, '0') > 0 && $change = '+' . $change;
                //追加涨副等信息
                $daymarket_data = [
                    'type' => 'daymarket',
                    'change' => $change,
                    'now_price' => $market_data['close'],
                    'api_form' => 'huobi_websocket',
                ];
                $kline_data = array_merge($kline_data, $daymarket_data);
                self::$marketKlineData[$period][$symbol_key]['kline_data'] = $kline_data;
            }
        }
    }

    // 订阅盘口数据
    protected function subscribeMarketDepth($con)
    {
        $currency_match = CurrencyMatch::getHuobiMatchs();
        foreach ($currency_match as $key => $value) {
            $param = [
                'symbol' => $value->match_name,
                'type' => 'step0',
            ];
            $topic = $this->makeSubscribeTopic($this->topicTemplate['sub']['market_depth'], $param);
            $sub_data = json_encode([
                'sub' => $topic,
                'id' => $topic,
            ]);

            $subscribed_data = $this->getSubscribed($topic);
            $match_data = $subscribed_data['match'] ?? [];
            $match_data[] = $value;
            $this->setSubscribed($topic, [
                'callback' => 'onMarketDepth',
                'match' => $match_data,
            ]);
            // 未订阅过的才能订阅
            if (is_null($subscribed_data)) {   
                $con->send($sub_data);
            }
        }
    }

    // 盘口数据回调
    protected function onMarketDepth($con, $data, $match)
    {
        try {
            $topic = $data->ch;
            $subscribed_data = $this->getSubscribed($topic);
            $currency_matches = $subscribed_data['match'];
            $limit = 10;
            $tick = $data->tick;
            $bids = array_slice($tick->bids, 0, $limit);
            $asks = array_slice($tick->asks, 0, $limit);
            krsort($asks);
            $asks = array_values($asks);
            foreach ($currency_matches as $key => $currency_match) {
                $depth_data = [
                    'type' => 'market_depth',
                    'symbol' => $currency_match->currency_name . '/' . $currency_match->legal_name,
                    'base-currency' => $currency_match->currency_name,
                    'quote-currency' => $currency_match->legal_name,
                    'currency_id' => $currency_match->currency_id,
                    'currency_name' => $currency_match->currency_name,
                    'legal_id' => $currency_match->legal_id,
                    'legal_name' => $currency_match->legal_name,
                    'bids' => $bids, //买入盘口
                    'asks' => $asks, //卖出盘口
                ];
                $symbol_key = $currency_match->currency_name . '.' . $currency_match->legal_name;
                self::$marketDepthData[$symbol_key] = $depth_data; 
            }
        } catch (\Throwable $th) {
           
        }       
    }

    /**
     * 发送盘口数据
     *
     * @return void
     */
    public function sendDepthData()
    {
        $market_depth = self::$marketDepthData;
        foreach ($market_depth as $depth_data) {
            SendMarket::dispatch($depth_data)->onQueue('market.depth');
        }
        self::$marketDepthData = [];
    }

    /**
     * 订阅全站交易(已完成)
     * @param mixed $name
     * @return void
     */
    public function subscribeMarketTrade($con)
    {
        $currency_match = CurrencyMatch::getHuobiMatchs();
        foreach ($currency_match as $key => $value) {
            $param = [
                'symbol' => $value->match_name,
            ];
            $topic = $this->makeSubscribeTopic($this->topicTemplate['sub']['market_trade'], $param);
            $sub_data = json_encode([
                'sub' => $topic,
                'id' => $topic,
            ]);
            $subscribed_data = $this->getSubscribed($topic);
            $match_data = $subscribed_data['match'] ?? [];
            $match_data[] = $value;
            $this->setSubscribed($topic, [
                'callback' => 'onMatchTrade',
                'match' => $match_data,
            ]);
            // 未订阅过的才能订阅
            if (is_null($subscribed_data)) {
                $con->send($sub_data);
            }
        }
    }

    //撮合交易全站交易数据回调
    protected function onMatchTrade($con, $data, $match)
    {
        $topic = $data->ch;
        $data = $data->tick->data;
        $subscribed_data = $this->getSubscribed($topic);
        $currency_matches = $subscribed_data['match'];
        foreach ($currency_matches as $key => $currency_match) {
            $symbol_key = $currency_match->currency_name . '.' . $currency_match->legal_name;
            $trade_data = [
                'type' => 'match_trade',
                'symbol' => $currency_match->currency_name . '/' . $currency_match->legal_name,
                'base-currency' => $currency_match->currency_name,
                'quote-currency' => $currency_match->legal_name,
                'currency_id' => $currency_match->currency_id,
                'currency_name' => $currency_match->currency_name,
                'legal_id' => $currency_match->legal_id,
                'legal_name' => $currency_match->legal_name,
                'data' => $data,
            ];
            self::$matchTradeData[$symbol_key] = $trade_data; 
        }
    }

    /**
     * 发送全站交易数据
     *
     * @return void
     */
    public function sendMatchTradeData()
    {
        $market_trade = self::$matchTradeData;
        foreach ($market_trade as $trade_data) {
            SendMarket::dispatch($trade_data)->onQueue('send:match:trade');
        }
        self::$matchTradeData = []; //发送完清空,以避免重复发送相同的数据
    }

    //取消订阅
    protected function unsubscribe()
    {
    }

    protected function onUnsubscribe()
    {
    }

    public function onMessage($con, $data)
    {
        $data = gzdecode($data);
        $data = json_decode($data, false, 512, JSON_BIGINT_AS_STRING); //对特大整数的处理，避免转换成科学记数法
        if (isset($data->ping)) {
            $this->onPong($con, $data);
        } elseif (isset($data->pong)) {
            $this->onPing($con, $data);
        } elseif (isset($data->id) && $this->getSubscribed($data->id) != null) {
            $this->onSubscribe($data);
        } elseif (isset($data->id)) {

        } else {
            $this->onData($con, $data);
        }
    }

    protected function onData($con, $data)
    {
        if (isset($data->ch)) {
            $subscribed = $this->getSubscribed($data->ch);
            if ($subscribed != null) {
                //调用回调处理
                $callback = $subscribed['callback'];
                $this->$callback($con, $data, $subscribed['match']);
            } else {
                //不在订阅中的数据
            }
        } else {
            echo '未知数据' . PHP_EOL;
            var_dump($data);
        }
    }

    public static function sendLeverHandle()
    {
        //echo date('Y-m-d H:i:s') . '定时器取价格' . PHP_EOL;
        $now = microtime(true);
        $master_start = microtime(true);
        //echo str_repeat('=', 80) . PHP_EOL;
        //echo date('Y-m-d H:i:s') . '开始发送价格到杠杆交易系统' . PHP_EOL;
        //echo '{' . PHP_EOL;
        try {
            $market_kiline = self::$marketKlineData['1day'] ?? null;
            if ($market_kiline) {
                foreach ($market_kiline as $key => $value) {
                    $kline_data = $value['kline_data'];
                    $start = microtime(true);
                    //echo "\t" . date('Y-m-d H:i:s') . ' 发送' . $key . ',价格:' . $kline_data['close'] . PHP_EOL;
                    $params = [
                        'legal_id' => $kline_data['legal_id'],
                        'legal_name' => $kline_data['legal_name'],
                        'currency_id' => $kline_data['currency_id'],
                        'currency_name' => $kline_data['currency_name'],
                        'now_price' => $kline_data['close'],
                        'now' => $now
                    ];
                    //价格大于0才进行任务推送
                    if (bc_comp($kline_data['close'], '0') > 0) {
                        LeverUpdate::dispatch($params)->onQueue('lever:update');
                    }
                    $end = microtime(true);
                    //echo "\t" . date('Y-m-d H:i:s') . $key . '处理完成,耗时' .($end - $start) . '秒' . PHP_EOL;
                }
                $master_end = microtime(true);
                //echo '}' . PHP_EOL;
                //echo date('Y-m-d H:i:s') . '杠杆交易系统处理完成,耗时' . ($master_end - $master_start) . '秒' . PHP_EOL;
                //echo str_repeat('=', 80) . PHP_EOL;
            }
        } catch (\Exception $e) {
            echo str_repeat('=', 80) . PHP_EOL;
            echo 'File:' . $e->getFile() . PHP_EOL;
            echo 'Line:' . $e->getLine() . PHP_EOL;
            echo 'Message:' . $e->getMessage() . PHP_EOL;
            var_dump(self::$marketKlineData);
            echo str_repeat('=', 80) . PHP_EOL;
        }
    }

    public function writeMarketKline()
    {
        if ($this->worker_id < 8) {
            $market_data = self::$marketKlineData;
            foreach ($market_data as $period => $data) {
                foreach ($data as $key => $symbol) {
                    // echo '处理' . $key . '.' . $period . '数据' . PHP_EOL;
                    $result = MarketHour::getEsearchMarketById(
                        $symbol['market_data']['base-currency'],
                        $symbol['market_data']['quote-currency'],
                        $period,
                        $symbol['market_data']['id']
                    );
                    if (isset($result['_source'])) {
                        $origin_data = $result['_source'];
                        bc_comp($symbol['kline_data']['high'], $origin_data['high']) < 0 && $symbol['kline_data']['high'] = $origin_data['high']; //新过来的价格如果不高于原最高价则不更新
                        bc_comp($symbol['kline_data']['low'], $origin_data['low']) > 0 && $symbol['kline_data']['low'] = $origin_data['low']; //新过来的价格如果不低于原最低价则不更新
                    }
                    SendMarket::dispatch($symbol['kline_data'])->onQueue('kline.all');
                    EsearchMarket::dispatch($symbol['market_data'])->onQueue('esearch:market:' . $period);
                    if ($period == '1day') {
                        //推送一天行情
                        $day_kline = $symbol['kline_data'];
                        $day_kline['type'] = 'kline';
                        SendMarket::dispatch($day_kline)->onQueue('kline.all');
                        //SendMarket::dispatch($symbol['kline_data'])->onQueue('kline.1day');
                        //存入数据库
                        CurrencyQuotation::getInstance($symbol['kline_data']['legal_id'], $symbol['kline_data']['currency_id'])
                            ->updateData([
                                'change' => $symbol['kline_data']['change'],
                                'now_price' => $symbol['kline_data']['close'],
                                'volume' => $symbol['kline_data']['volume'],
                                'open' => $symbol['kline_data']['open'],
                                'close' => $symbol['kline_data']['close'],
                                'high' => $symbol['kline_data']['high'],
                                'low' => $symbol['kline_data']['low'],
                            ]);
                    } else {
                        continue;
                    }
                }
            }
            self::$marketKlineData = [];
        }
    }

    protected function calcIncreasePair($kline_data)
    {
        $open = $kline_data['open'];
        $close = $kline_data['close'];;
        $change_value = bc_sub($close, $open);
        $change = bc_mul(bc_div($change_value, $open), 100, 2);
        return $change;
    }

    //心跳响应
    protected function onPong($con, $data)
    {
        //echo '收到心跳包,PING:' . $data->ping . PHP_EOL;
        $send_data = [
            'pong' => $data->ping,
        ];
        $send_data = json_encode($send_data);
        $con->send($send_data);
        //echo '已进行心跳响应' . PHP_EOL;
    }

    public function ping($con)
    {
        $ping = time();
        //echo '进程' . $this->worker_id . '发送ping服务器数据包,ping值:' . $ping . PHP_EOL;
        $send_data = json_encode([
            'ping' => $ping,
        ]);
        $con->send($send_data);
        // $this->pingTimer = Timer::add($this->server_time_out, function () use ($con) {
        //     $msg = '进程' . $this->worker_id . '服务器响应超时,连接关闭' . PHP_EOL;
        //     echo $msg;
        //     $this->close($msg);
        // }, [], false);
    }

    protected function onPing($con, $data)
    {
        $this->pingTimer && Timer::del($this->pingTimer);
        $this->pingTimer = null;
        //echo '进程' . $this->worker_id . '服务器正常响应中,pong:' . $data->pong. PHP_EOL;
    }
}
