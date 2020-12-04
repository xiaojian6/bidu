<?php

namespace App\Models;

class WalletLog extends Model
{
    //
    protected $table = "wallet_log";

    public $timestamps = false;

    /**
     * 模型日期的存储格式
     *
     * @var string
     */
    protected $dateFormat = 'U';
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * 一对一关联account_log模型
     */
    public function accountLog()
    {
        return $this->belongsTo(Accountlog::class,'account_log_id','id')->withDefault();
    }
    /**
     * 一对一关联users_wallet模型
     */
    public function UsersWallet(){
        return $this->belongsTo(UsersWallet::class,'wallet_id','id')->withDefault();
    }
}
