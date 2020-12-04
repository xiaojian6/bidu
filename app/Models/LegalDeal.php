<?php

namespace App\Models;

class LegalDeal extends Model
{
    protected $table = 'legal_deal';
    public $timestamps = false;
    protected $appends = [
        'deal_money',
        'currency_name',
        'type',
        'account_number',
        'phone',
        'seller_name',
        'price',
        'hes_account',
        'hes_realname',
        'way_name',
        'format_create_time',
        'is_seller',
        'user_cash_info',
        'seller_phone',
        'user_realname',
        'bank_address',
        'coin_code',
        'sell_info',
    ];

    public function getSellInfoAttribute()
    {
        return $this->seller ?? [];
    }

    public function getUserCashInfoAttribute()
    {
        $user = $this->user()->getResults();
        if (!$user) {
            return [];
        }
        $cashinfo = $user->cashinfo;
        if (!$cashinfo) {
            return [];
        }
        return $cashinfo;
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }

    public function legalDealSend()
    {
        return $this->belongsTo(LegalDealSend::class, 'legal_deal_send_id', 'id');
    }

    public function getCreateTimeAttribute()
    {
        return date('H:i m/d', $this->attributes['create_time']);
    }
    public function getFormatCreateTimeAttribute()
    {
        return date('Y-m-d H:i:s', $this->attributes['create_time']);
    }

    public function getDealMoneyAttribute()
    {
        $legal = LegalDealSend::find($this->attributes['legal_deal_send_id']);
        if (!empty($legal)) {
            return bcmul($this->attributes['number'], $legal->price, 6);
        }
        return 0;
    }


    public function getCurrencyNameAttribute()
    {
        $legal = LegalDealSend::find($this->attributes['legal_deal_send_id']);
        if (!empty($legal)) {
            return $legal->currency_name;
        }
        return '';
    }

    public function getSellerPhoneAttribute()
    {
        $seller = Seller::find($this->attributes['seller_id']);
        if (empty($seller)) return null;
        $user = Users::find($seller->user_id);
        if (!empty($user)) {
            return $user->phone;
        } else {
            return null;
        }
    }

    public function getTypeAttribute()
    {
        return $this->hasOne(LegalDealSend::class, 'id', 'legal_deal_send_id')->value('type');
    }

    public function getAccountNumberAttribute()
    {
        return $this->hasOne(Users::class, 'id', 'user_id')->value('account_number');
    }

    public function getPhoneAttribute()
    {
        return $this->hasOne(Users::class, 'id', 'user_id')->value('account_number');
    }

    public function getSellerNameAttribute()
    {
        return $this->hasOne(Seller::class, 'id', 'seller_id')->value('name');
    }

    public function getBankAddressAttribute()
    {
        return $this->hasOne(Seller::class, 'id', 'seller_id')->value('bank_address');
    }

    public function getPriceAttribute()
    {
        return $this->hasOne(LegalDealSend::class, 'id', 'legal_deal_send_id')->value('price');
    }

    public function getHesAccountAttribute()
    {
        $legal_send = LegalDealSend::find($this->attributes['legal_deal_send_id']);
        if (!empty($legal_send)) {
            $seller = Seller::find($legal_send->seller_id);
            if (!empty($seller)) {
                if ($legal_send->way == 'bank') {
                    return $seller->bank_account;
                } elseif ($legal_send->way == 'we_chat') {
                    return $seller->wechat_account;
                } elseif ($legal_send->way == 'ali_pay') {
                    return $seller->ali_account;
                }
            }
        }
        return '';
    }

    public function getHesRealnameAttribute()
    {
        $seller = Seller::find($this->attributes['seller_id']);
        if (!empty($seller)) {
            $real = UserReal::where('user_id', $seller->user_id)->where('review_status', 2)->first();
            if (!empty($real)) {
                return $real->name;
            }
        }
        return '';
    }

    public function getUserRealnameAttribute()
    {
        $user_real = UserReal::where('user_id', $this->attributes['user_id'])->first();
        if (empty($user_real)) {
            return '';
        }
        return $user_real->name;
    }

    public function getWayNameAttribute()
    {
        return LegalDealSend::find($this->attributes['legal_deal_send_id'])->way_name;
    }

    // 是否卖方
    public function getIsSellerAttribute()
    {
        $user_id = Users::getUserId();
        $legal_send = LegalDealSend::find($this->attributes['legal_deal_send_id']);
        $seller = Seller::find($this->attributes['seller_id']);
        if ($legal_send == null || $seller == null) {
            return 0;
        }
        if (($this->attributes['user_id'] == $user_id) && ($legal_send->type == 'buy')) {
            return 1;
        } elseif (($legal_send->type == 'sell') && ($user_id == $seller->user_id)) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function cancelLegalDealById($legal_deal_id, $type = AccountLog::LEGAL_DEAL_USER_SELL_CANCEL)
    {
        //DB::beginTransaction();
        try {
            $legal_deal = LegalDeal::lockForUpdate()
                ->find($legal_deal_id);
            $legal_deal_send = LegalDealSend::lockForUpdate()
                ->find($legal_deal->legal_deal_send_id);
            $users_wallet = UsersWallet::where('user_id', $legal_deal->user_id)
                ->where('currency', $legal_deal_send->currency_id)
                ->lockForUpdate()
                ->first();
            if ($legal_deal_send->type == 'buy') { //求购
                // do something
                // if ($users_wallet->legal_balance < $number){
                //     DB::rollback();
                //     return $this->error('您的法币余额不足');
                // }
                // $legal_deal_send->surplus_number += $legal_deal->number;//
                $legal_deal_send->surplus_number = bc_add($legal_deal_send->surplus_number, $legal_deal->number, 5);//
                if ($legal_deal_send->surplus_number > 0) {
                    $legal_deal_send->is_done = 0;
                }
                $legal_deal_send->surplus_number = bc_add($legal_deal_send->surplus_number, $legal_deal->number, 5);//
                if ($legal_deal_send->surplus_number > 0) {
                    $legal_deal_send->is_done = 0;
                }
                $legal_deal_send->save();
                //减少冻结
                $result = change_wallet_balance($users_wallet, 1, -$legal_deal->number, $type, '取消出售给商家法币,减少冻结', true);
                if ($result !== true) {
                    throw new \Exception('取消失败:减少冻结资金失败');
                }
                //增加余额(从冻结流向余额)
                $result = change_wallet_balance($users_wallet, 1, $legal_deal->number, $type, '取消出售给商家法币,冻结撤回增加余额');
                if ($result !== true) {
                    throw new \Exception('取消失败:撤回冻结到余额失败');
                }
                //撤回手续费
                if (bc_comp($legal_deal->out_fee, '0') > 0) {
                    $result = change_wallet_balance($users_wallet, 1, $legal_deal->out_fee, AccountLog::LEGAL_CANCEL_TRADE_FREE, '取消出售给商家法币,撤回手续费');
                    if ($result !== true) {
                        throw new \Exception('取消失败:撤回冻结到余额失败');
                    }
                }
            } elseif ($legal_deal_send->type == 'sell') { //出售
                //$legal_deal_send->surplus_number += $legal_deal->number;
                $legal_deal_send->surplus_number = bc_add($legal_deal_send->surplus_number, $legal_deal->number, 5);
                if ($legal_deal_send->surplus_number > 0) {
                    $legal_deal_send->is_done = 0;
                }
                $legal_deal_send->save();
            }
            $legal_deal->is_sure = 2;
            $legal_deal->save();
            $legal_deal->update_time = time();
            //DB::commit();
            return true;

        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
            return false;
        }
    }

    public function getCoinCodeAttribute()
    {
        $send = $this->legalDealSend()->getResults();
        return $send->coin_code ?? '';
    }
}
