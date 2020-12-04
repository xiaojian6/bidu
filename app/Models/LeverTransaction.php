<?php

namespace App\Models;


use App\Jobs\LeverExpected;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Events\LeverTradeClosedEvent;
use App\Utils\RPC;
use App\Models\Setting;


use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Channels\SmsMessage\SmsFactory;
use Illuminate\Support\Facades\Cache;

use App\Jobs\{LeverClose, LeverPushTrade, LeverHandle, SendMarket};

class LeverTransaction extends Model
{
    // 下单方向
    const BUY = 1; //买入
    const SELL = 2; //卖出

    // 交易状态
    const ENTRUST = 0; //挂单中
    const TRANSACTION = 1; //交易中
    const CLOSING = 2; //平仓中
    const CLOSED = 3; //已平仓
    const CANCEL = 4; //已撤单

    // 平仓原因
    const CLOSED_BY_UNKNOW = 0; // 未知
    const CLOSED_BY_USER_SELF = 1; // 用户平仓
    const CLOSED_BY_MARKET_PRICE = 1; // 市场价平仓
    const CLOSED_BY_OUT_OF_MONEY = 2; // 暴仓
    const CLOSED_BY_TARGET_PROFIT = 3; // 止盈平仓SendMarket
    const CLOSED_BY_STOP_LOSS = 4; // 止损平仓
    const CLOSED_BY_ADMIN = 5; // 后台管理员平仓

    protected $table = 'lever_transaction';
    public $timestamps = false;

    protected $appends = [
        'account_number',
        'time',
        'symbol',
        'profits',
        'status_name',
        'type_name',
    ];

    protected static $statusList = [
        '挂单中',
        '交易中',
        '平仓中',
        '已平仓',
        '已撤单',
    ];

    protected static $typeList = [
        '',
        '买入',
        '卖出',
    ];

    protected static $closedTypeList = [
        '',
        '市价',
        '暴仓',
        '止盈',
        '止损',
        '后台',
    ];

    public static function enumStatus()
    {
        return self::$statusList;
    }

    public static function enumClosedType()
    {
        return self::$closedTypeList;
    }

    public function getClosedTypeNameAttribute()
    {
        $closed_type = $this->attributes['closed_type'];
        return array_key_exists($closed_type, self::$closedTypeList) ? self::$closedTypeList[$closed_type] : '';
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id')->withDefault();
    }

    public function getMobileAttribute()
    {
        $user = $this->user()->getResults();
        return $user->phone ?? '';
    }

    public function getAccountNumberAttribute()
    {
        $user = $this->user()->getResults();
        return $user->account_number ?? '';
    }

    public function getStatusNameAttribute()
    {
        $status = $this->attributes['status'] ?? 0;
        return self::$statusList[$status];
    }

    public function getTypeNameAttribute()
    {
        $type = $this->attributes['type'] ?? 0;
        return self::$typeList[$type];
    }

    public function getTimeAttribute()
    {
        return date('Y-m-d H:i:s', $this->attributes['create_time'] ?? 0);
    }

    public function getHandleTimeAttribute()
    {
        $handle_time = intval($this->attributes['handle_time']);
        return $handle_time != 0 ? date('Y-m-d H:i:s', $handle_time) : '';
    }

    public function getTransactionTimeAttribute()
    {
        $transaction_time = intval($this->attributes['transaction_time']);
        return $transaction_time != 0 ? date('Y-m-d H:i:s', $transaction_time) : '';
    }

    public function getCompleteTimeAttribute()
    {
        $complete_time = intval($this->attributes['complete_time']);
        return $complete_time != 0 ? date('Y-m-d H:i:s', $complete_time) : '';
    }

    public function getSymbolAttribute()
    {
        $currency_id = $this->getAttribute('currency');
        $legal_id = $this->getAttribute('legal');
        $currency_match = CurrencyMatch::where('currency_id', $currency_id)
            ->where('legal_id', $legal_id)
            ->first();
        return $currency_match ? $currency_match->symbol : '';
    }

    /**
     * 取每单盈利
     *
     * @return void
     */
    public function getProfitsAttribute()
    {
        $profits = 0;
        $type = $this->getAttribute('type');
        $number = $this->getAttribute('number');
        $status = $this->getAttribute('status');
        if ($status == self::ENTRUST || $status == self::CANCEL) {
            return 0.00;
        }
        //$multiple = $this->getAttribute('multiple');
        //$multiple_number = bc_mul($number, $multiple);
        $update_price = $this->getAttribute('update_price');
        $price = $this->getAttribute('price');
        $diff = $type == self::BUY ? bc_sub($update_price, $price) : bc_sub($price, $update_price);
        $profits = bc_mul($diff, $number);
        return $profits;
    }

    public static function leverMultiple($key = 0)
    {
        $data["muit"] = LeverMultiple::where("type","=",1)->select("value")->get()->toArray();
        $data["share"] = LeverMultiple::where("type","=",2)->select("value")->get()->toArray();
        // var_dump($data);die;
        // $data = array(
        //     "5" => "5倍",
        //     "10" => "10倍",
        //     "20" => "20倍",
        //     "50" => "50倍",
        // );
        if (!empty($key) && in_array($key, $data)) {
            return $data[$key];
        } else {
            return $data;
        }
    }

    /**
     * 订单价格更新
     *
     * @param integer $legal_id 法币id
     * @param integer $currency_id 交易币id
     * @param float $price 当前交易对最新价格
     * @param float $timestamp 毫秒级时间戳
     * @return void
     */
    public static function newPrice($legal_id, $currency_id, $price, $timestamp = null)
    {
        $timestamp == null && $timestamp = microtime(true);
        if (empty($legal_id) || empty($currency_id) || empty($price)) {
            return false;
        }
        $params = [
            'legal_id' => $legal_id,
            'currency_id' => $currency_id,
            'now_price' => $price,
            'now' => $timestamp,
        ];
        $start = microtime(true);
        //先批量更新指定交易对未平仓的交易的最新价格
        LeverTransaction::where("legal", $legal_id)
            ->where("currency", $currency_id)
            ->where("status", '<=', self::TRANSACTION)
            ->update([
                'update_price' => $price,
                'update_time' => $timestamp,
            ]);




        //凯云增加爆仓短信提醒  开始
        $levers=LeverTransaction::where("legal",$legal_id)
            ->where("currency", $currency_id)
            ->where("status", self::TRANSACTION)
            ->select("id","user_id","create_time")
            ->groupBy("user_id")
            ->get();
        if(count($levers)>0){


            foreach ($levers as $trade){
                //推送风险率和订单
//                LeverPushTrade::dispatch($trade->user_id,$legal_id,$currency_id)->onQueue("lever:push:trade");
                //预期风险率报警提醒
                LeverExpected::dispatch($trade->user_id,$legal_id)->onQueue('lever:push:expected');
            }
        }

        //凯云增加爆仓短信提醒  结束



        $end = microtime(true);
        LeverHandle::dispatch($params)->onQueue('lever:handle');
        // echo '更新持仓订单价格消耗:' . ($end - $start) . '秒' . PHP_EOL;
    }

    /**
     * 价格变动处理逻辑
     *
     * @param integer $legal_id 法币id
     * @param integer $currency_id 交易币id
     * @param float $price 当前交易对最新价格
     * @param float $timestamp 毫秒级时间戳
     * @return void
     */
    public static function tradeHandle($legal_id, $currency_id, $price, $timestamp = null)
    {
        $timestamp == null && $timestamp = microtime(true);
        if (empty($legal_id) || empty($currency_id) || empty($price)) {
            return false;
        }
        $total_start = microtime(true);
        $start = microtime(true);
        //激活满足条件的挂单
        self::entrustActivate();
        $end = microtime(true);
        echo '激活订单消耗:' . ($end - $start) . '秒' . PHP_EOL.
        $start = microtime(true);
        //止盈止亏处理
        self::checkNeedStopPriceTrade(0, $legal_id, $currency_id);
        $end = microtime(true);
        echo '止盈止亏消耗:' . ($end - $start) . '秒' . PHP_EOL;
        $start = microtime(true);
        $levers = LeverTransaction::where("legal", $legal_id)
            ->where("currency", $currency_id)
            ->where("status", self::TRANSACTION)
            ->select("id", "user_id", "create_time")
            ->groupBy('user_id')
            ->get();
        if (count($levers) > 0) {
            foreach ($levers as $trade) {
                //推送风险率和订单
                LeverPushTrade::dispatch($trade->user_id, $legal_id, $currency_id)->onQueue('lever:push:trade');
                self::handleUserLever($trade->user_id, $legal_id);
            }
        } else {
            echo  '法币id:' . $legal_id . ',交易币id:' . $currency_id . '无数据' . PHP_EOL;
        }
        $end = microtime(true);
        echo '检测风险率消耗:' . ($end - $start) . '秒' . PHP_EOL;
        echo '总计消耗:' . ($end - $total_start) . '秒' . PHP_EOL;
    }

    /**
     * 更新用户指定交易对的杠杆交易
     *
     * @param integer $user_id 用户id
     * @param integer $legal_id 法币id
     * @param integer $currency_id 交易币id
     * @return void
     */
    protected static function handleUserLever($user_id, $legal_id, $currency_id = 0)
    {

        if (empty($user_id)) {
            return false;
        }
        DB::beginTransaction();
        try {
            $legal_wallet = UsersWallet::where("user_id", $user_id)
                ->where("currency", $legal_id)
                ->lockForUpdate()
                ->first();

            if (empty($legal_wallet)) {
                throw new \Exception('钱包不存在');
            }
            //取交易对总盈利和总保证金
            $profit_results = self::getUserProfit($user_id, $legal_id, $currency_id);
            extract($profit_results);
            //是否满足爆仓条件
            $need_burst = self::checkBurst($legal_wallet);
            if (!$need_burst) {
                throw new \Exception('不满足爆仓条件');
            }
            //对用户交易对做平仓中标记
            $affect_result = self::setUserLeverCover($user_id, $legal_id, $currency_id, 2);
            if (count($affect_result) <= 0) {
                throw new \Exception('爆仓状态标记失败');
            }
            $diff = 0;
            $change = bc_add($profits_total, $caution_money_total);
            $after_balance = bc_add($legal_wallet->lever_balance, $change);
            
            // 如果余额不够扣就抹去不够扣的金额
            if (bc_comp($after_balance, '0') < 0) {
                $diff = $after_balance;
                $change = -$legal_wallet->lever_balance;
            }
            
            $legal_name = $legal_wallet->currency_name;
            $extra_data = serialize([
                'legal_name' => $legal_name,
                'affect_result' => $affect_result,
                'balance' => $legal_wallet->lever_balance,
                'caution_money_total' => $caution_money_total,
                'profits_total' => $profits_total,
                'diff' => $diff,
            ]);


            //12.11 liang add  添加爆仓的时候给用户发送短信  开始

            //$bb=self::sendSms($legal_wallet->user_id);
//            var_dump($bb);die;

            //12.11 liang add  添加爆仓的时候给用户发送短信  结束


            $result = change_wallet_balance(
                $legal_wallet,
                3,
                $change,
                AccountLog::LEVER_TRANSACTION_FROZEN,
                '暴仓处理' . $legal_name . '余额(退回保证金:' . $caution_money_total . ',结算总盈亏:' . $profits_total . ')',
                false,
                0,
                $diff == 0 ? 0 : 1, //1代表有差额
                $extra_data,
                true, //余额为0仍然要平仓
                true //余额不足时扣成负数
            );
            if ($result !== true) {
                throw new \Exception($result);
            }




            DB::commit();
            LeverClose::dispatch($affect_result, false)->onQueue('lever:close');
            return true;
        } catch (\Exception $ex) {
            DB::rollBack();
            // $path = base_path() . '/storage/logs/lever/';
            // $filename = date('Ymd') . '.log';
            // file_exists($path) || @mkdir($path);
            // error_log(date('Y-m-d H:i:s') . ' File:' . $ex->getFile() . ', Line:' . $ex->getLine() . ', Message:' . $ex->getMessage() . PHP_EOL, 3, $path . $filename);
            return false;
        }
    }








    /**
     * 取用户盈利和保证金
     *
     * @param integer $user_id 用户id
     * @param integer $legal_id 法币id
     * @param integer $currency_id 交易币id
     * @return array
     */
    public static function getUserProfit($user_id, $legal_id = 0, $currency_id = 0)
    {
        $profits_total = '0'; //交易对盈亏总额
        $caution_money_total = '0'; //交易对可用本金总额
        $origin_caution_money_total = '0'; //交易对原始保证金
        try {
            //优先让数据库计算盈亏和保证金
            $user_profit = LeverTransaction::where('status', self::TRANSACTION)
                ->where('user_id', $user_id)
                ->where(function ($query) use ($legal_id, $currency_id) {
                    $legal_id > 0 && $query->where('legal', $legal_id);
                    $currency_id > 0 && $query->where('currency', $currency_id);
                })
                ->select('user_id')
                ->selectRaw('SUM((CASE `type` WHEN 1 THEN `update_price` - `price` WHEN 2 THEN `price` - `update_price` END) * `number`) AS `profits_total`')
                ->selectRaw('SUM(`caution_money`) AS `caution_money_total`')
                ->selectRaw('SUM(`origin_caution_money`) AS `origin_caution_money_total`')
                ->groupBy('user_id')
                ->first();
            if (!$user_profit) {
                $levers = LeverTransaction::where(function ($query) use ($legal_id, $currency_id) {
                    $legal_id > 0 && $query->where("legal", $legal_id);
                    $currency_id > 0 && $query->where("currency", $currency_id);
                })->where("user_id", $user_id)->where("status", self::TRANSACTION)->lockForUpdate()->get();
                if (count($levers) <= 0) {
                    throw new \Exception('没有需要处理的交易');
                }
                //计算交易对总盈亏
                foreach ($levers as $trade) {
                    $caution_money_total = bc_add($caution_money_total, $trade->caution_money);
                    $origin_caution_money_total = bc_add($origin_caution_money_total, $trade->origin_caution_money);
                    $profits_total = bc_add($profits_total, $trade->profits);
                }
            } else {
                $caution_money_total = $user_profit->caution_money_total;
                $origin_caution_money_total = $user_profit->origin_caution_money_total;
                $profits_total = $user_profit->profits_total;
            }
        } catch (\Exception $e) {
            //echo $e->getMessage();
        }
        return [
            'profits_total' => $profits_total,
            'caution_money_total' => $caution_money_total,
            'origin_caution_money_total' => $origin_caution_money_total,
        ];
    }

    /**
     * 平仓用户指定交易对交易(只是改变状态,避免价格再被更新)
     *
     * @param integer $user_id
     * @param integer $legal_id
     * @param integer $currency_id
     * @return array
     */
    protected static function setUserLeverCover($user_id, $legal_id = 0, $currency_id = 0, $closed_type = 0)
    {
        DB::beginTransaction();
        $trades = LeverTransaction::where(function ($query) use ($legal_id, $currency_id) {
            $legal_id > 0 && $query->where("legal", $legal_id);
            $currency_id > 0 && $query->where("currency", $currency_id);
        })->where("user_id", $user_id)->where("status", self::TRANSACTION);
        $list = $trades->pluck('id')->all(); //记录下标记的交易id
        $result = $trades->update([
            'closed_type' => $closed_type,
            'status' => self::CLOSING,
            'handle_time' => microtime(true),
        ]);
        DB::commit();
        return $result > 0 ? $list : [];
    }

    /**
     * 平仓
     *
     * @param \App\LeverTransaction $lever_transaction
     * @param integer $closed_type 平仓类型
     * 
     * @return bool
     */
    public static function leverClose($lever_transaction, $closed_type = self::CLOSED_BY_USER_SELF)
    {
        try {
            DB::beginTransaction();
            if (empty($lever_transaction)) {
                throw new \Exception('交易不存在');
            }
            $last_price = self::getLastPrice($lever_transaction->legal, $lever_transaction->currency);
            $lever_transaction->refresh();
            // if ($lever_transaction->status != self::TRANSACTION) {
            //     throw new \Exception('该笔交易状态异常,不能平仓' . $lever_transaction->status);
            // }
            //更新状态
            $lever_transaction->update_price = $last_price;
            $lever_transaction->update_time = microtime(true);
            $lever_transaction->status = self::CLOSING;
            $lever_transaction->handle_time = microtime(true);
            $result = $lever_transaction->save();
            if (!$result) {
                throw new \Exception('平仓失败:锁定交易状态失败');
            }
            $legal_wallet = UsersWallet::where("user_id", $lever_transaction->user_id)
                ->where("currency", $lever_transaction->legal)
                ->lockForUpdate()
                ->first();
            if (empty($legal_wallet)) {
                throw new \Exception('钱包不存在');
            }
            //计算盈亏
            $profit = $lever_transaction->profits;
            $change = bc_add($lever_transaction->caution_money, $profit); //算上本金
            //从钱包处理资金
            $pre_result = bc_add($legal_wallet->lever_balance, $change);
            $diff = 0;
            /*
            // 是否余额不够扣除
            if (bc_comp($pre_result, 0) < 0) {
                $change = -$legal_wallet->lever_balance;
                $diff = $pre_result;
            }
            */
            $extra_data = [
                'trade_id' => $lever_transaction->id,
                'caution_money' => $lever_transaction->caution_money,
                'profit' => $profit,
                'diff' => $diff,
            ];
            $result = change_wallet_balance(
                $legal_wallet,
                3,
                $change,
                AccountLog::LEVER_TRANSACTION_ADD,
                '平仓资金处理',
                false,
                0,
                $diff == 0 ? 0 : 1, //1代表有差额
                serialize($extra_data),
                true, //余额为0仍然要平仓
                true  //允许余额不足时扣成负数
            );
            if ($result !== true) {
                throw new \Exception($result);
            }
            $lever_transaction->refresh();
            $lever_transaction->status = self::CLOSED;
            $lever_transaction->fact_profits = $profit;
            $lever_transaction->complete_time = microtime(true);
            $lever_transaction->closed_type = $closed_type; //平仓类型
            $result = $lever_transaction->save();
            if (!$result) {
                throw new \Exception('平仓失败:更新处理状态失败');
            }


//            self::sendSms($lever_transaction->user_id);//平仓发短信


            DB::commit();
            $lever_trades = collect([$lever_transaction]);
            event(new LeverTradeClosedEvent($lever_trades));
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }



    //发送短信
    public static function sendSms($user_id){
        $user=Users::find($user_id);
        $mobile =$user->phone;
        $email =$user->email;


//        $mobile ="15890603450";
//        $email ="1980817435@qq.com";

        if($mobile){
            //发送短信
            // $address_url="http://guobi.top/api/sms_send";
            // $data=[];
            // $data['user_string']=$mobile;
            // $data['scene']='trade';
            // $data['country_code']=$user->country_code;

            // $lian = RPC::apihttp($address_url,'POST',$data);

            $sender = SmsFactory::getSmsSender($user->country_code == 86 ? 0 : 1);
            $verification_code = self::createSmsCode(4);
            $send_result = $sender->send($mobile, 'Ip0B', ['code'=>$verification_code], $user->country_code);
            if (!$send_result) {
                //发送失败处理逻辑
                echo 'send fails';
            }

        }else{
            //发送邮箱
            $data=[];

            $data['user_string']=$email;
            $data['scene']='trade';
            $data['country_code']=$user->country_code;
            $address_url="http://www.bibex.org/api/sendMail_baocang";
            $lian = RPC::apihttp($address_url,'POST',$data);
        }

    }


    /**
     * 生成短信验证码
     * @param int $num  验证码位数
     * @return string
     */
    public static function createSmsCode($num = 6)
    {
        //验证码字符数组
        $n_array = range(0, 9);
        //随机生成$num位验证码字符
        $code_array = array_rand($n_array, $num);
        //重新排序验证码字符数组
        shuffle($code_array);
        //生成验证码
        $code = implode('', $code_array);
        return $code;
    }


    /**
     * 取交易对最新价格(优先从行情获取)
     *
     * @param integer $legal_id 法币id
     * @param integer $currency_id 交易币id
     * @return integer
     */
    public static function getLastPrice($legal_id, $currency_id)
    {
        // 优先从ElasticSearch取最新价格
        $last_price = 0;
        $currency=Currency::find($currency_id);
        $legal=Currency::find($legal_id);
        $last = MarketHour::getLastEsearchMarket($currency->name, $legal->name);
        if(count($last) > 0) {
            $last = reset($last);
            $last_price = $last['close'];
        } else {
            // 再从行情取最新价格
            $last = CurrencyQuotation::where('legal_id', $legal_id)
                ->where('currency_id', $currency_id)
                ->first();
            if ($last) {
                $last_price = $last->now_price;
            } else {
                $last = TransactionComplete::orderBy('id', 'desc')
                    ->where("currency", $currency_id)
                    ->where("legal", $legal_id)
                    ->first();
               $last && $last_price = $last->price;
            }
        }
        return $last_price;
    }

    /**
     * 取钱包的风险率(请传法币钱包)
     *
     * @param App\UsersWallet $wallet
     * @return float
     */
    public static function getWalletHazardRate($wallet)
    {
        $hazard_rate = 0;
        $total_money = 0;
        if (!$wallet) {
            return $hazard_rate;
        }
        $profit_result = self::getUserProfit($wallet->user_id, $wallet->currency);
        extract($profit_result);
        $wallet->refresh();
        $balance = $wallet->lever_balance;
        $total_money = bc_add($balance, $origin_caution_money_total);
        if (bc_comp($origin_caution_money_total, '0') <> 0) {
            $hazard_rate = bc_mul(bc_div(bc_add($total_money, $profits_total), $origin_caution_money_total), 100, 2);
        }
        return $hazard_rate;
    }

    /**
     * 检测是否达到爆仓条件
     *
     * @param \App\UsersWallet $user_wallet
     * @return bool 达到返回真，否则返回假
     */
    public static function checkBurst($user_wallet)
    {
        try {
            if (!($user_wallet instanceof UsersWallet)) {
                throw new \Exception('钱包无效');
            }
            
            //取交易对总盈利和总保证金
            $profit_results = self::getUserProfit($user_wallet->user_id, $user_wallet->legal_id);
            extract($profit_results);
            //判断盈亏
            if (bc_comp($profits_total, '0') >= 0) {
                throw new \Exception('不存在亏损,无须平仓');
            }
            $change = bc_add($profits_total, $caution_money_total);
            //如果本金足以抵亏就返回
            if (bc_comp($change, '0') >= 0) {
                throw new \Exception('本金充足,无须平仓');
            }
            //本金亏完,判断余额是否够扣,够直接返回
            //直接判断总额
            $total_money = bc_add($user_wallet->lever_balance, $caution_money_total);
            $pre_total_checked = bc_add($total_money, $profits_total);
            if (bc_comp($pre_total_checked, '0') > 0) {
                throw new \Exception('总资金充足,无须平仓');
            }
             
            //使用风控率来控制爆仓
            // $lever_burst_hazard_rate = Setting::getValueByKey('lever_burst_hazard_rate', 0);
            // $hazard_rate = self::getWalletHazardRate($user_wallet);
            // $result = bc_comp($hazard_rate, $lever_burst_hazard_rate) <= 0;
            // $param = [
            //     'hazard_rate' => $hazard_rate,
            //     'lever_burst_hazard_rate' => $lever_burst_hazard_rate,
            // ];
            // $path = base_path() . '/storage/logs/lever/';
            // $filename = date('Ymd') . '.log';
            // file_exists($path) || @mkdir($path);
            // error_log(date('Y-m-d H:i:s') . ' result: ' . var_export($result, true) . ', param:' . var_export($param, true) . PHP_EOL, 3, $path . $filename);
            return true;
        } catch (\Exception $e) {
            $path = base_path() . '/storage/logs/lever/';
            $filename = date('Ymd') . '.log';
            file_exists($path) || @mkdir($path);
            error_log(date('Y-m-d H:i:s') . ' File:' . $e->getFile() . ', Line:' . $e->getLine() . ', Message:' . $e->getMessage() . PHP_EOL, 3, $path . $filename);
            return false;
        }
    }

    
    /**
     * 取设置了止盈止亏价并且价格满足的交易
     *
     * @return void
     */
    public static function checkNeedStopPriceTrade($user_id = 0, $legal_id = 0, $currency_id = 0)
    {
        /**
         关于止盈止亏：
            1.买入:当前行情价格【大于等于】“止盈价”时，触发止盈;当前行情价格【小于等于】“止亏价”时，触发止亏;
            2.买入:当前行情价格【小于等于】“止盈价”时，触发止盈;当前行情价格【大于等于】“止亏价”时，触发止亏;
         */
        try {
            DB::beginTransaction();
            $need_check_lever = LeverTransaction::where('status', self::TRANSACTION)
                ->where(function ($query) use ($user_id, $legal_id, $currency_id) {
                    $user_id > 0 && $query->where('user_id', $user_id);
                    $legal_id > 0 && $query->where('legal', $legal_id);
                    $currency_id > 0 && $query->where('currency', $currency_id);
                })->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('type', self::BUY)->where(function ($query) {
                            $query->where(function ($query) {
                                $query->whereRaw('`update_price` >= `target_profit_price`')->where('target_profit_price', '>', 0);
                            })->orWhere(function ($query) {
                                $query->whereRaw('`update_price` <= `stop_loss_price`')->where('stop_loss_price', '>', 0);
                            });
                        });
                    })->orWhere(function ($query) {
                        $query->where('type', self::SELL)->where(function ($query) {
                            $query->where(function ($query) {
                                $query->whereRaw('`update_price` <= `target_profit_price`')->where('target_profit_price', '>', 0);
                            })->orWhere(function ($query) {
                                $query->whereRaw('`update_price` >= `stop_loss_price`')->where('stop_loss_price', '>', 0);
                            });
                        });
                    });
                })->lockForUpdate();
            $task_list = $need_check_lever->pluck('id')->all();
            // 标注上平仓类型
            $need_check_lever->get()->each(function ($item) {
                if ($item->profits > 0) {
                    $item->closed_type = self::CLOSED_BY_TARGET_PROFIT;
                } else {
                    $item->closed_type = self::CLOSED_BY_STOP_LOSS;
                }
                $item->save();
            });
            $result = $need_check_lever->update([
                'status' => self::CLOSING,
                'handle_time' => microtime(true),
            ]);
            DB::commit();
            $result > 0 && LeverClose::dispatch($task_list, true)->onQueue('lever:close');
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
    
    /**
     * 委托激活
     *
     * @return void
     */
    public static function entrustActivate()
    {
        /*
        逻辑:分析师认为当价格走到某个区间后会停止,然后逆向走,在停止时期进行反向投资来赚取利润
        例如:
        (1)用户认为某个币在指定周期内跌到5元已跌到谷底不可能再跌了,预测接下来行情会上涨,那应该设定在跌到5元时进行买入,等行情上涨赚钱
        (2)用户认为某个币在指定周期内涨到1000元已经达到顶峰,预测接下来行情会下跌,那应该设定在涨到1000元时进行卖出,等行情下跌赚钱
        程序逻辑:
        (1)卖出:当前价格 [大于等于] 设置价格时触发(等涨到指定价格时卖出坐等下跌)
        (2)买入:当前价格 [小于等于] 设置价格时触发(等跌到指定价格时买入坐等上涨)
        */
        /*
        SELECT *
        FROM lever_transaction
        WHERE
        `status`=0
        AND
        (
        `type`=1 AND `update_price` <= `origin_price`
        OR 
        `type`=2 AND `update_price` >= `origin_price`
        )
        */
        $trades = LeverTransaction::where('status', LeverTransaction::ENTRUST)
            ->where(function ($query) {
                $query->orWhere(function ($query) {
                    $query->where('type', LeverTransaction::BUY)
                    ->whereRaw('`update_price` <= `origin_price`');
                })->orWhere(function ($query) {
                    $query->orWhere('type', LeverTransaction::SELL)
                    ->whereRaw('`update_price` >= `origin_price`');
                }); 
            });
        $lists = $trades->pluck('id')->all();
        $result = $trades->update([
            'transaction_time' => microtime(true),
            'status' => LeverTransaction::TRANSACTION,
        ]);
        return $result > 0 ? $lists : []; 
    }

    /**
     * 向前台推送已经删除的杠杆订单(已平仓的、已撤单的)
     *
     * @param Collection $lever_trades
     * @return void
     */
    public static function pushDeletedTrade(Collection $lever_trades)
    {
        $grouped = $lever_trades->groupBy('user_id');
        $grouped->each(function (Collection $item, $key) {
            SendMarket::dispatch([
                'type' => 'lever_closed',
                'to' => $key,
                'id' => $item->pluck('id')->all(),
            ])->onQueue('lever:send:closed');
        });
    }
}
