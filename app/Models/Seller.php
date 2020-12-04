<?php

namespace App\Models;

class Seller extends Model
{
    protected $table = 'seller';
    public $timestamps = false;
    protected $appends = ['bank_name','account_number','currency_name','prove_email','prove_mobile','prove_real','prove_level','is_myseller'];

    public function getBankNameAttribute(){
        return $this->hasOne(Bank::class,'id','bank_id')->value('name');
    }

    public function getAccountNumberAttribute(){
        return $this->hasOne(Users::class,'id','user_id')->value('account_number');
    }

    public function getCurrencyNameAttribute(){
        return $this->hasOne(Currency::class,'id','currency_id')->value('name');
    }

    public function setWechatNicknameAttribute($value){
        $this->attributes['wechat_nickname'] = base64_encode($value);
    }

    public function getWechatNicknameAttribute(){
        if (!empty($this->attributes['wechat_nickname'])){
            return base64_decode($this->attributes['wechat_nickname']);
        }else{
            return '';
        }

    }

    public function getCreateTimeAttribute(){
        return date('Y-m-d H:i:s',$this->attributes['create_time']);
    }

    public function legalDeal(){
        return $this->hasOne(LegalDeal::class,'seller_id','id');
    }

    public function getProveEmailAttribute(){
        $result = $this->hasOne(Users::class,'id','user_id')->value('email');
        if (empty($result)){
            return 0;
        } else{
            return 1;
        }
    }

    public function getProveMobileAttribute(){
        $result = $this->hasOne(Users::class,'id','user_id')->value('phone');
        if (empty($result)){
            return 0;
        } else{
            return 1;
        }
    }


    public function getProveRealAttribute(){
        $result = UserReal::where('user_id',$this->attributes['user_id'])->where('review_status',2)->first();
        if (empty($result)){
            return 0;
        } else{
            return 1;
        }
    }

    public function getProveLevelAttribute(){
        $result = UserReal::where('user_id',$this->attributes['user_id'])->where('review_status',2)->first();
        if (!empty($result) && !empty($result->front_pic)){
            return 1;
        }
        return 0;
    }

    public function getIsMysellerAttribute(){
        if ($this->attributes['user_id'] == Users::getUserId()){
            return 1;
        }
        return 0;
    }

}
