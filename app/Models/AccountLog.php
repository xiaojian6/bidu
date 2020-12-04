<?php

/**
 * Created by PhpStorm.
 * User: swl
 * Date: 2018/7/3
 * Time: 10:23
 */

namespace App\Models;

use Illuminate\Support\Facades\DB;

class AccountLog extends Model
{
    protected $table = 'account_log';
    public $timestamps = false;
    const CREATED_AT = 'created_time';
    protected $appends = [
        'account_number',
        'account',
        'currency_name', //币种
        'before', //交易前
        'after', //交易后
        'transaction_info' //交易信息
    ];

    const ADMIN_LEGAL_BALANCE = 1; //后台调节法币账户余额
    const ADMIN_LOCK_LEGAL_BALANCE = 2; //后台调节法币账户锁定余额
    const ADMIN_CHANGE_BALANCE = 3; //后台调节币币账户余额
    const ADMIN_LOCK_CHANGE_BALANCE = 4; //后台调节币币账户锁定余额
    const ADMIN_LEVER_BALANCE = 5; //后台调节杠杆账户余额
    const ADMIN_LOCK_LEVER_BALANCE = 6; //后台调节杠杆账户锁定余额

    const WALLET_CURRENCY_OUT = 7; //提币记录
    const WALLET_CURRENCY_IN = 8; //充币记录

    const WALLET_LEGAL_OUT = 9; //法币划出
    const WALLET_LEGAL_IN = 10; //法币划入
    const WALLET_CHANGE_IN = 11; //币币划入
    const WALLET_CHANGE_OUT = 12; //币币划出
    const WALLET_LEVER_IN = 13; //杠杆划入
    const WALLET_LEVER_OUT = 14; //杠杆划出

    const WALLET_ACCOUNT_TRANSFER_OUT = 15; // 钱包账户划出
    const WALLET_ACCOUNT_TRANSFER_IN = 16; // 钱包账户划入

    const INVITATION_TO_RETURN = 33; //邀请返佣
    const LEGAL_DEAL_SEND_SELL = 60; //商家发布法币出售
    const LEGAL_DEAL_USER_SELL = 61; //出售给商家法币
    const LEGAL_USER_BUY = 62; //用户购买商家法币成功
    const LEGAL_SELLER_BUY = 63; //商家购买用户法币成功
    const LEGAL_DEAL_USER_SELL_CANCEL = 64; //出售给商家法币-取消
    const ADMIN_SELLER_BALANCE = 70; //后台调节商家余额
    const LEGAL_DEAL_BACK_SEND_SELL = 71; //商家撤回发布法币出售
    const LEGAL_DEAL_ERROR_SEND_SELL = 72; //商家撤回发布法币出售
    const LEGAL_DEAL_AUTO_CANCEL = 68; //自动取消法币交易
    const BTC_TRANSFER_FEE = 80; //打入BTC手续费
    const ETH_TRANSFER_FEE = 81; //打入ETH手续费
    const TOKENS_WRAPPING = 82; //代币归拢

    const TRANSACTIONOUT_SUBMIT_REDUCE = 21; //提交卖出，扣除

    const TRANSACTIONIN_REDUCE = 22; //买入扣除
    const TRANSACTIONIN_SELLER = 23; //扣除卖方
    const TRANSACTIONIN_SUBMIT_REDUCE = 24; //提交买入，扣除

    const TRANSACTIONIN_REDUCE_ADD = 25; //买方增加币
    const TRANSACTIONIN_SELLER_ADD = 26; //卖方增加cny

    const TRANSACTIONIN_REVOKE_ADD = 27; //撤销增加
    const TRANSACTIONOUT_REVOKE_ADD = 28; //撤销增加

    const TRANSACTION_FEE = 29; //卖出手续费

    const LEVER_TRANSACTION = 30; //杠杆交易扣除保证金
    const LEVER_TRANSACTION_ADD = 31; //平仓增加
    const LEVER_TRANSACTION_FROZEN = 32; //爆仓冻结
    const LEVER_TRANSACTION_OVERNIGHT = 34; //隔夜费
    const LEVER_TRANSACTION_FEE = 35; //交易手续费
    const LEVER_TRANSACTIO_CANCEL = 36; //杠杆交易取消
    const CANDY_LEVER_BALANCE = 37; //通证兑换杠杆币增加
    const LEVER_TRANSACTION_FEE_CANCEL = 38; //交易手续费

    const WALLETOUT = 99; //用户申请提币
    const WALLETOUTDONE = 100; //用户提币成功
    const WALLETOUTBACK = 101; //用户提币失败
    const TRANSACTIONIN_IN_DEL = 102; //取消买入交易
    const TRANSACTIONIN_OUT_DEL = 103; //取消买出交易

    const CHANGE_LEVER_BALANCE = 104; //杠杆交易账户变化

    const REWARD_CANDY = 105; //奖励通证
    const REWARD_CURRENCY = 106; //奖励数字货币

    const CANDY_TOUSDT_CANDY = 107; //通证兑换USDT
    const ADMIN_CANDY_BALANCE = 108; //后台调节通证

    const SELLER_BACK_SEND = 299; //杠杆交易账户变化
    const CHANGEBALANCE = 401; //转账
    const LTC_IN = 301; //来自矿机的转账
    const LTC_SEND = 302; //转账余额至矿机

    const ETH_EXCHANGE = 200; //充币增加余额
    
    const CHAIN_RECHARGE = 200;

    //c2c交易
    const C2C_DEAL_SEND_SELL = 201; //用户发布法币出售
    const C2C_DEAL_AUTO_CANCEL = 202; //自动取消c2c法币交易
    const C2C_DEAL_USER_SELL = 203; //出售给用户法币
    const C2C_USER_BUY = 204; //用户购买法币成功
    const C2C_DEAL_BACK_SEND_SELL = 205; //商家撤回发布法币出售

    const C2C_TRADE_FEE = 230; //C2C交易手续费
    const C2C_CANCEL_TRADE_FEE = 231; //C2C交易取消撤回手续费
    const LEGAL_TRADE_FREE = 232; //法币交易手续费
    const LEGAL_CANCEL_TRADE_FREE = 233; //法币交易取消撤回手续费

    const WALLET_LEGAL_LEVEL_OUT = 206; //法币(c2c)转入杠杆
    const WALLET_LEGAL_LEVEL_IN = 207; //法币(c2c)转入杠杆
    const WALLET_LEVEL_LEGAL_OUT = 208; //杠杆转入法币(c2c)
    const WALLET_LEVEL_LEGAL_IN = 209; //杠杆转入法币(c2c)
    const WALLET_DONGJIEGANGGAN = 210;
    const WALLET_JIEDONGGANGGAN = 211; //审核不通过解冻杠杆冻结

    const PROFIT_LOSS_RELEASE = 212; //历史盈亏释放,增加杠杆币

    const JC_INTEGRAL_EXCHANGE_CURRENCY = 220;

    const MATCH_TRANSACTION_SELL_FEE = 301; //撮合交易[卖出]手续费
    const MATCH_TRANSACTION_BUY_FEE = 302; //撮合交易<买入>成功手续费

    const MATCH_TRANSACTION_CANCEL_SELL_FEE = 303; //撮合交易取消[卖出]撤回手续费
    const MATCH_TRANSACTION_CANCEL_BUY_FEE = 304; //撮合交易取消<买入>撤回手续费

    const GAME_SHIFT_TO = 305; //游戏转入

    const AGENT_JIE_TC_MONEY = 306; //代理商结算头寸收益
    const AGENT_JIE_SX_MONEY = 307; //代理商结算手续费收益

    const BIBI_RATE_RETURN = 308; //币币交易手续费返佣


    const BIBI_AIRDROP_RETURN = 401; //空投奖励发放
    const BIBI_LOCKED_RELEASE = 402; //锁仓释放
    const ADD_BIBI_LOCKED_RELEASE = 404; //锁仓释放,币币余额增加
    const ADMIN_LOCKED_BALANCE = 403; //后台调节锁仓账户余额


    const BIBI_DEPOSIT_FIXED_SELE = 405; //币币余额转入定期存币生息账户
    const BIBI_DEPOSIT_CURRENT_SELE = 406; //币币余额转入定期存币生息账户
    const BIBI_DEPOSIT_FIXED_RETURN = 407; //定期存币生息到期，返还本息
    const BIBI_DEPOSIT_CURRENT_RETURN = 408; //活期存币生息手动转出，返还本息

    const BIBI_DEPOSIT_EXPORE = 409; //币币余额转出至生息账户

    const BIBI_CURRENT_DEPOSIT_INCOME_RETURN = 410; //活期存币生息归拢
    const BIBI_FIXED_DEPOSIT_INCOME_RETURN = 411; //定期存币生息归拢
    const BIBI_DEPOSIT_FIXED_IN = 412; //定期存币生息自动转入，返还本息
    const BIBI_DEPOSIT_CURRENT_IN = 413; //活期存币生息手动转入，返还本息

    const RECHANGE_TRANS_TO_LOCKED = 501; //充币成功，转入锁仓账户
    const RECHANGE_TRANS_FROM_BIBIWALLET = 502; //系统自动转移至锁仓账户
    const RECHANGE_WALLET_LOCKED_RELEASED = 503; //锁仓账户释放
    const RECHANGE_WALLET_LOCKED_RELEASED_TO_BIBI = 503; //锁仓账户释至币币账户
    const RECHANGE_WALLET_LOCKED_RELEASED_YEAR_RATE = 504; //年利率奖励释放

    const USET_BALANCE_GIFT_TO_PSE_BIBI = 601;          //根据USDT余额赠送PSE

    public function getAccountNumberAttribute()
    {
        return $this->hasOne(Users::class, 'id', 'user_id')->value('account_number');
    }

    public function getAccountAttribute()
    {
        $value = $this->hasOne(Users::class, 'id', 'user_id')->value('phone');
        if (empty($value)) {
            $value = $this->hasOne(Users::class, 'id', 'user_id')->value('email');
        }
        return $value;
    }

    public function getCreatedTimeAttribute()
    {
        $value = $this->attributes['created_time'];
        return $value ? date('Y-m-d H:i:s', $value) : '';
    }

    public function getBeforeAttribute()
    {
        return $this->walletLog()->value('before');
    }
    public function getAfterAttribute()
    {
        return $this->walletLog()->value('after');
    }

    public function getTransactionInfoAttribute()
    {
        $type1 = [
            '0' => '无', '1' => '法币交易', '2' => '币币交易', '3' => '杠杆交易'
        ];
        $type2 = ['', '[锁定]'];
        $balance_type = $this->walletLog()->value('balance_type');
        $lock_tpye = $this->walletLog()->value('lock_type');
        array_key_exists($balance_type, $type1) ?: $balance_type = 0;
        array_key_exists($lock_tpye, $type2) ?: $lock_tpye = 0;
        return $type1[$balance_type] . $type2[$lock_tpye];
    }

    public function getCurrencyNameAttribute()
    {
        return $this->hasOne(Currency::class, 'id', 'currency')->value('name');
    }

    public static function insertLog($data = array(), $data2 = array())
    {
        $data = is_array($data) ? $data : func_get_args();
        $log = new self();
        $log->user_id = $data['user_id'] ?? false;;
        $log->value = $data['value'] ?? '';
        $log->created_time = $data['created_time'] ?? time();
        $log->info = $data['info'] ?? '';
        $log->type = $data['type'] ?? 0;
        $log->currency = $data['currency'] ?? 0;
        $data_wallet['balance_type'] = $data2['balance_type'] ?? 0;
        $data_wallet['wallet_id'] = $data2['wallet_id'] ?? 0;
        $data_wallet['lock_type'] = $data2['lock_type'] ?? 0;
        $data_wallet['before'] = $data2['before'] ?? 0;
        $data_wallet['change'] = $data2['change'] ?? 0;
        $data_wallet['after'] = $data2['after'] ?? 0;
        $data_wallet['memo'] = $data['info'] ?? 0;
        $data_wallet['create_time'] = $data2['create_time'] ?? time();
        //dd($data_wallet);
        try {
            DB::transaction(function () use ($log, $data_wallet) {
                $log->save();
                $log->walletLog()->create($data_wallet);
            });
            return true;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
            return false;
        }
    }

    public static function newinsertLog($data = array(), $data2 = array())
    {
        $data = is_array($data) ? $data : func_get_args();
        $log = new self();
        $log->user_id = $data['user_id'] ?? false;;
        $log->value = $data['value'] ?? '';
        $log->created_time = $data['created_time'] ?? time();
        $log->info = $data['info'] ?? '';
        $log->type = $data['type'] ?? 0;
        $log->currency = $data['currency'] ?? 0;
        //        $data_wallet['balance_type'] = $data2['balance_type']?? 0;
        //        $data_wallet['wallet_id'] = $data2['wallet_id']?? 0;
        //        $data_wallet['lock_type'] = $data2['lock_type']?? 0;
        //        $data_wallet['before'] = $data2['before']?? 0;
        //        $data_wallet['change'] = $data2['change']?? 0;
        //        $data_wallet['after'] = $data2['after']?? 0;
        //        $data_wallet['memo'] = $data['info']?? 0;
        //        $data_wallet['create_time'] = $data2['create_time']?? time();
        //dd($data_wallet);
        try {
            DB::transaction(function () use ($log) {
                $log->save();
                //                $log->walletLog()->create($data_wallet);
            });
            return true;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
            return false;
        }
    }


    public static function getTypeInfo($type)
    {
        switch ($type) {

            case self::ADMIN_LEGAL_BALANCE:
                return '后台调节法币账户余额';
                break;
            case self::ADMIN_LOCK_LEGAL_BALANCE:
                return '后台调节法币账户锁定余额';
                break;
            case self::ADMIN_CHANGE_BALANCE:
                return '后台调节币币账户余额';
                break;
            case self::ADMIN_LOCK_CHANGE_BALANCE:
                return '后台调节币币账户锁定余额';
                break;
            case self::ADMIN_LEVER_BALANCE:
                return '后台调节杠杆账户余额';
                break;
            case self::ADMIN_LOCK_LEVER_BALANCE:
                return '后台调节杠杆账户锁定余额';
                break;
            case self::WALLET_LEGAL_OUT:
                return '法币账户转出至交易账户';
                break;
            case self::WALLET_LEGAL_IN:
                return '交易账户转入至法币账户';
                break;
            case self::WALLET_CHANGE_OUT:
                return '交易账户转出至法币账户';
                break;
            case self::WALLET_CHANGE_IN:
                return '法币账户转入交易账户';
                break;
            case self::WALLET_CHANGE_LEVEL_IN:
                return '杠杆账户转入交易账户';
                break;
            case self::WALLET_CHANGE_LEVEL_OUT:
                return '交易账户转出至杠杆账户';
                break;
            case self::WALLET_LEVEL_OUT:
                return '杠杆账户转出至交易账户';
                break;
            case self::WALLET_LEVEL_IN:
                return '交易账户转入杠杆账户';
                break;
            case self::INVITATION_TO_RETURN:
                return '邀请返佣金';
                break;
            case self::WALLETOUT:
                return '用户提币';
                break;
            case self::TRANSACTIONIN_IN_DEL:
                return '取消买入交易';
                break;
            case self::TRANSACTIONIN_OUT_DEL:
                return '取消卖出交易';
                break;
            case self::INTO_TRA_FB:
                return '美丽链法币交易余额转入';
                break;
            case self::INTO_TRA_BB:
                return '美丽链币币交易余额转入';
                break;
            case self::INTO_TRA_GG:
                return '美丽链杠杆交易余额转入';
                break;
            case self::WALLET_LEGAL_LEVEL_OUT:
                return '法币转入杠杆,法币减少';
                break;
            case self::WALLET_LEGAL_LEVEL_IN:
                return '法币转入杠杆，杠杆增加';
                break;
            case self::WALLET_LEVEL_LEGAL_OUT:
                return '杠杆转法币审核通过,杠杆减少';
                break;
            case self::WALLET_LEVEL_LEGAL_IN:
                return '杠杆转法币审核通过，法币增加';
                break;
            case self::WALLET_DONGJIEGANGGAN:
                return '杠杆转法币,冻结杠杆转化值';
                break;
            case self::WALLET_JIEDONGGANGGAN:
                return '杠杆转法币,审核不通过解冻';
                break;
            case self::CANDY_TOUSDT_CANDY:
                return '通证兑换USDT';
                break;
            case self::CANDY_LEVER_BALANCE:
                return '通证兑换，杠杆币增加';
                break;
            case self::PROFIT_LOSS_RELEASE:
                return '历史盈亏释放,增加杠杆币';
                break;
            case self::REWARD_CANDY:
                return '奖励通证';
                break;
            case self::BIBI_RATE_RETURN:
                return '币币交易手续费返佣';
                break;
            case self::REWARD_CURRENCY:
                return '奖励数字货币';
                break;
            case self::ADMIN_CANDY_BALANCE:
                return '后台调节通证';
                break;
            case self::BIBI_AIRDROP_RETURN:
                return '空投奖励发放';
                break;
            case self::BIBI_LOCKED_RELEASE:
                return '锁仓释放';
                break;
            case self::ADMIN_LOCKED_BALANCE:
                return '后台调节锁仓账户余额';
                break;
            case self::ADD_BIBI_LOCKED_RELEASE:
                return '锁仓释放，币币余额增加';
                break;
            case self::BIBI_DEPOSIT_FIXED_SELE:
                return '币币余额转入定期存币生息账户';
                break;
            case self::BIBI_DEPOSIT_CURRENT_SELE:
                return '币币余额转入活期存币生息账户';
                break;
            case self::BIBI_DEPOSIT_FIXED_RETURN:
                return '定期存币生息到期自动转出';
                break;
            case self::BIBI_DEPOSIT_CURRENT_RETURN:
                return '活期存币生息手动转出，返还本息';
                break;
            case self::BIBI_DEPOSIT_EXPORE:
                return '币币余额转出至存币生息账户';
                break;
            case self::BIBI_CURRENT_DEPOSIT_INCOME_RETURN:
                return '活期存币生息归拢';
                break;
            case self::BIBI_FIXED_DEPOSIT_INCOME_RETURN:
                return '定期存币生息归拢';
                break;
            case self::BIBI_DEPOSIT_FIXED_IN:
                return '定期存币生息自动转入，返还本息';
                break;
            case self::RECHANGE_TRANS_TO_LOCKED:
                return '充币成功，转入锁仓账户';
                break;
            case self::RECHANGE_TRANS_FROM_BIBIWALLET:
                return '系统自动转移至锁仓账户';
                break;
            case self::RECHANGE_WALLET_LOCKED_RELEASED_TO_BIBI:
                return '锁仓账户释放';
                break;
            case self::RECHANGE_WALLET_LOCKED_RELEASED_TO_BIBI:
                return '锁仓账户释至币币账户';
                break;
            case self::RECHANGE_WALLET_LOCKED_RELEASED_YEAR_RATE:
                return '年利率奖励释放';
                break;
            case self::USET_BALANCE_GIFT_TO_PSE_BIBI:
                return '每日USDT余额赠送PSE';
                break;
            default:
                return '暂无此类型';
                break;
        }
    }
    /*public static function getTypeInfo($type)
    {
        switch ($type) {
            case self::BUY_BLOCK_CHAIN:
                return '购买区块链';
                break;
            case self::ADJUDT_SUB_BALANCE:
                return '后台调节账户余额';
                break;
            case self::ADMIN_LOCK_BALANCE:
                return '后台调节锁定余额';
                break;
            case self::ADMIN_REMAIN_LOCK_BALANCE:
                return '后台调节锁定余额变动剩余锁定余额';
                break;
            case self::ACCEPTOR_SELL:
                return '用户提现承兑申请';
                break;
            case self::ACCEPTOR_RECHARGE:
                return '用户充值承兑确认';
                break;
            case self::ACCEPTOR_RECHARGE_VAR:
                return '用户充值承兑手续费';
                break;
            case self::ACCEPTOR_RECHARGE_DEC:
                return '确认用户充值，承兑商充值额度减少';
                break;
            case self::ACCEPTOR_CASH_INC:
                return '确认用户充值，承兑商提现额度增加';
                break;
            case self::ACCEPTOR_CASH_DEC:
                return '确认用户提现，承兑商提现额度减少';
                break;
            case self::ACCEPTOR_RECHARGE_INC:
                return '确认用户提现，承兑商充值额度增加';
                break;
            case self::ACCEPTOR_SELL_RETURN:
                return '取消用户提现承兑申请';
                break;
            default:
                return '暂无此类型';
                break;
        }
    }*/

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    //关联钱包记录模型
    public function walletLog()
    {
        return $this->hasOne(WalletLog::class, 'account_log_id', 'id')->withDefault();
    }
}
