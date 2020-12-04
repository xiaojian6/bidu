<?php

/**
 * create by vscode
 * @author lion
 */
namespace App\Models;


use Illuminate\Support\Facades\DB;
use App\Utils\RPC;

class TransactionIn extends Model
{
    protected $table = 'transaction_in';
    public $timestamps = false;
    protected $appends = [
        'account_number',
        'currency_name',
        'legal_name'
    ];

    public function getAccountNumberAttribute()
    {
        return $this->hasOne(Users::class, 'id', 'user_id')->value('account_number');
    }

    public function getCreateTimeAttribute()
    {
        $value = $this->attributes['create_time'];
        return $value ? date("Y-m-d H:i:s", $value) : '';
    }

    /**
     * 匹配交易
     *
     * @param App\TransactionOut $out 卖出委托模型实例
     * @param float $num 匹配数量
     * @param App\Users $user 买入用户模型实例
     * @param integer $legal_id 法币币种id
     * @param integer $currency_id 交易币种id
     * @return void
     */
    public static function transaction($out, $num, $user, $legal_id, $currency_id)
    {
        $now = time();
        if (empty($out) || empty($num) || empty($user)) {
            return false;
        }
        DB::beginTransaction();
        try {
            //买方法币
            $to_legal = UsersWallet::where("user_id", $user->id)
                ->where("currency", $legal_id)
                ->lockForUpdate()
                ->first();
            //卖方法币
            $out_legal = UsersWallet::where("user_id", $out->user_id)
                ->where("currency", $legal_id)
                ->lockForUpdate()
                ->first();
            //买方币
            $to_currency = UsersWallet::where("user_id", $user->id)
                ->where("currency", $currency_id)
                ->lockForUpdate()
                ->first();
            //卖方币
            $out_currency = UsersWallet::where("user_id", $out->user_id)   
                ->where("currency", $currency_id)
                ->lockForUpdate()
                ->first();
            $out_user = Users::find($out->user_id);

            if (empty($to_currency) || empty($out_currency) || empty($out_user) || empty($to_legal) || empty($out_legal)) {
                throw new \Exception('参数错误');
            }

            bc_comp($num, $out->number) > 0 && $num = $out->number;
            $cny = bc_mul($num, $out->price);
            //查询并计算买方手续费
            $currency_matches = CurrencyMatch::where("legal_id", $legal_id)
                ->where("currency_id", $currency_id)
                ->select()
                ->first();
            $exchange_rate = $currency_matches->exchange_rate;
            $quantity = bc_div(bc_mul($num, $exchange_rate), 100);

            //扣除买方的法币
            $result = change_wallet_balance($to_legal, 2, -$cny, AccountLog::TRANSACTIONIN_REDUCE, '挂买' . $currency_matches->symbol . '匹配成功：扣除买方' . $currency_matches->legal_name);
            if ($result !== true) {
                throw new \Exception($result);
            }
            
            //增加卖方法币并记录日志
            $result = change_wallet_balance($out_legal, 2, $cny, AccountLog::TRANSACTIONIN_SELLER_ADD, $currency_matches->symbol . '卖出被匹配,增加卖方' . $currency_matches->legal_name);
            if ($result !== true) {
                throw new \Exception($result);
            }

            //检测卖方冻结交易币是否充足
            if (bc_comp($out_currency->lock_change_balance, $num) < 0) {
                throw new \Exception('匹配到的卖方委托存在异常,委托编号:' . $out->id);
            }
            //扣除卖方冻结的交易币
            $result = change_wallet_balance($out_currency, 2, -$num, AccountLog::TRANSACTIONIN_SELLER, $currency_matches->symbol . '卖出被匹配,扣除卖方已冻结' . $currency_matches->currency_name, true);
            if ($result !== true) {
                throw new \Exception($result);
            }

            //增加买方的交易币
            $result = change_wallet_balance($to_currency, 2, $num, AccountLog::TRANSACTIONIN_REDUCE_ADD, '挂买' . $currency_matches->symbol . '匹配成功:增加买方' . $currency_matches->currency_name);
            if ($result !== true) {
                throw new \Exception($result);
            }

            if ($num >= $out->number) {
                if($out->delete() < 1) {
                    throw new \Exception('撮合发生异常：清理失败，委托编号：' . $out->id);
                }
            } else {
                $out->number = bc_sub($out->number, $num);
                if (!$out->save()) {
                    throw new \Exception('撮合发生异常：更新卖方剩余数量失败，委托编号：' . $out->id);
                }
            }

            //插入完成记录
            $complete = new TransactionComplete();
            $complete->way = 2; //挂买
            $complete->user_id = $user->id; //买方
            $complete->from_user_id = $out->user_id; //卖方
            $complete->price = $out->price;
            $complete->number = $num;
            $complete->currency = $currency_id;
            $complete->legal = $legal_id;
            $complete->in_fee = $quantity; //写入手续费
            $complete->create_time = $now;
            $complete->save();
            //扣除买方手续费
            $result = change_wallet_balance(
                $to_currency,
                2,
                -$quantity,
                AccountLog::MATCH_TRANSACTION_BUY_FEE,
                '买入匹配成功,扣除手续费{id:' . $complete->id . ',匹配数量:' . $num . ',费率:' . $exchange_rate . '%}'
            );
            if ($result !== true) {
                throw new \Exception($result);
            }
            DB::commit();
            $total = TransactionComplete::where('currency', $currency_id)
                ->where('legal', $legal_id)
                ->where('create_time', '>=', strtotime(date('Y-m-d')))
                ->sum('number');
            $data = [
                'legal_id' => $legal_id,
                'currency_id' => $currency_id,
                'volume' => $total,
                'now_price' => sctonum($out->price)
            ];
            MarketHour::batchWriteMarketData($currency_id, $legal_id, $num,sctonum($out->price), 1); //写入行情数据
            CurrencyQuotation::updateTodayPriceTable($data); //更新今日价格表
            //推送K线行情
            $market_hour = MarketHour::getTimelineInstance(5, $currency_id, $legal_id, 1, $now);
            $kline_data = [
                'type' => 'kline',
                'period' => '1min',
                'currency_id' => $currency_id,
                'currency_name' => $complete->currency_name,
                'legal_id' => $legal_id,
                'legal_name' => $complete->legal_name,
                'symbol' =>  $complete->currency_name . '/' . $complete->legal_name,
                'open' => $market_hour->start_price,
                'close' => $market_hour->end_price,
                'high' => $market_hour->highest,
                'low' => $market_hour->mminimum,
                'volume' => $market_hour->number,
                'time' => $now * 1000,
            ];
            UserChat::sendText($kline_data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function pushNews()
    {
        $data = self::orderBy('price', 'asc')->take(5)->get();
        $send = array("type" => "in", "data" => $data, "content" => "");
        return UserChat::sendText($send);
    }

    /**
     * 币种
     *
     * @return void
     */
    public function currencycoin()
    {
        return $this->belongsTo(Currency::class, 'currency', 'id')->withDefault();
    }

    /**
     * 法币币种
     *
     * @return void
     */
    public function legalcoin()
    {
        return $this->belongsTo(Currency::class, 'legal', 'id')->withDefault();
    }

    public function getCurrencyNameAttribute()
    {
        return $this->currencycoin()->value('name');
    }

    public function getLegalNameAttribute()
    {
        return $this->legalcoin()->value('name');
    }

    /**
     * 定义与用户表的一对一关系
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id')->withDefault();
    }
}
