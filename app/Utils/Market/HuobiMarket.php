<?php

namespace App\Utils\Market;

use Illuminate\Support\Facades\DB;
//use App\CurrencyMatch;
use App\Models\CurrencyMatch;
use App\HuobiSymbol;
use App\CurrencyQuotation;
use App\Models\MarketHour;
use App\Models\UserChat;

class HuobiMarket
{
    protected static $config = [];

    protected static $huobiInterfaceInstance = null;

    protected $params = [];

    protected static $period = [
        '1min' => 5,
        '5min' => 6,
        '15min' => 1,
        '30min' => 7,
        '60min' => 2,
        '4hour' => 3,
        '1day' => 4,
        '1week' => 8,
        '1mon' => 9,
        '1year' => 10,
    ];

    public static function getConfig($key = null, $default = '')
    {
        if (is_null($key)) {
            return self::$config;
        } else {
            return self::$config[$key] ?? $default;
        }   
    }

    public static function setConfig($key, $value = null)
    {
        if (is_array($key)) {
            self::$config = $key;
        } else {
            self::$config[$key] = $value;
        }
    }

    public function setParam($key, $value)
    {
        if (is_array($key)) {
            $this->params = $key;
        } else {
            $this->params[$key] = $value;
        }
    }

    /**
     * 获得一个火币接口实例
     *
     * @return \App\Utils\Market\Huobi
     */
    public static function getHuobiInterfaceInstance()
    {
        if (!self::$huobiInterfaceInstance) {
            $account_id = self::getConfig('account_id');
            $access_key = self::getConfig('access_key');
            $secret_key = self::getConfig('secret_key');
            self::$huobiInterfaceInstance = new Huobi($account_id, $access_key, $secret_key);
        }
        return self::$huobiInterfaceInstance;
    }

    /**
     * 获取K线
     *
     * @param string $symbol
     * @param string $period,可选值:1min, 5min, 15min, 30min, 60min, 1day, 1mon, 1week, 1year
     * @param integer $size
     * @return void
     */
    public static function getHistoryKline($symbol, $period, $size = 150)
    {
        $original_value = date_default_timezone_get();
        $huobi = self::getHuobiInterfaceInstance();
        $result = $huobi->get_history_kline($symbol, $period, $size);
        date_default_timezone_set($original_value);
        return $result;
    }

    /**
     * 获取所有k线
     *
     * @param string $period k线分辨类型
     * @param \Illuminate\Console\OutputStyle $output 命令行输出
     * @return void
     */
    public static function getAllHistoryKline($period)
    {
        $type = self::$period[$period];
        $all_symbols = HuobiSymbol::all();
        //$huobi_array = $all_symbols->pluck('symbol')->all();
        $currency_match = CurrencyMatch::getHuobiMatchs();
        echo date('Y-m-d H:i:s') . ',行情请求中,请稍等' . PHP_EOL;
        $time = strtotime(date('Y-m-d H:i'));
        $start = microtime(true);
        $fails = 0;
        DB::beginTransaction();
        foreach ($currency_match as $key => $value) {
            $result = self::getHistoryKline($value->match_name, $period, 3);
            if ($result && $result->status == 'ok') {
                $data = $result->data;
                if ($data[0]->id >= $time) {
                    //echo '取第二条数据' . PHP_EOL;
                    $info = $data[1];
                } else {
                    echo '取第一条数据' . PHP_EOL;
                    $info = $data[0];
                }
                //var_dump($info);
                $kline_data[] = [
                    'type' => 'kline',
                    'period' => '1min',
                    'currency_id' => $value->currency_id,
                    'currency_name' => $value->currency_name,
                    'legal_id' => $value->legal_id,
                    'legal_name' => $value->legal_name,
                    'open' => $info->open,
                    'close' => $info->close,
                    'high' => $info->high,
                    'low' => $info->low,
                    'symbol' => $value->currency_name . '/' . $value->legal_name,
                    'volume' => $info->amount,
                    'time' => $info->id * 1000,
                ];
                //K线行情插入
                $market_data = [
                    'open' => $info->open,
                    'close' => $info->close,
                    'high' => $info->high,
                    'low' => $info->low,
                    'amount' => $info->amount,
                    'start_price' => $info->open,
                    'end_price' => $info->close,
                    'highest' => $info->high,
                    'mminimum' => $info->low,
                    'number' => $info->amount,
                ];
                MarketHour::batchWriteKlineMarket($value->currency_id, $value->legal_id, $market_data, 2, $info->id);
            } else {
                $fails++;
            }
        }
        DB::commit();
        $end = microtime(true);
        echo date('Y-m-d H:i:s') . ',本次共请求'. count($currency_match) . '次,失败' . $fails . '次,耗时' . ($end - $start) . '秒' . PHP_EOL;
        UserChat::sendChatMulti($kline_data); //推送K线行情
        return true;
    }
}
