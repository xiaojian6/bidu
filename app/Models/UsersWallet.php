<?php

/**
 * Created by PhpStorm.
 * User: swl
 * Date: 2018/7/3
 * Time: 10:23
 */

namespace App\Models;

use Illuminate\Support\Facades\Config;

class UsersWallet extends Model
{
    protected $table = 'users_wallet';
    public $timestamps = false;
    /*const CREATED_AT = 'create_time';*/
    const CURRENCY_DEFAULT = "USDT";

    protected $hidden = [
        'private',
    ];

    protected $appends = [
        'currency_name',
        'currency_type',
        'contract_address',
        'is_legal',
        'is_lever',
        'is_change',
        'usdt_price',
    ];

    public function getCreateTimeAttribute()
    {
        $value = $this->attributes['create_time'];
        return $value ? date('Y-m-d H:i:s', $value) : '';
    }

    public function getCurrencyTypeAttribute()
    {
        return $this->hasOne(Currency::class, 'id', 'currency')->value('type');
    }

    // public function getExrateAttribute()
    // {
    //     // $value = $this->attributes['create_time'];
    //     return $ExRate = Setting::getValueByKey('USDTRate',6.5);;
    // }

    public function getCurrencyNameAttribute()
    {
        return $this->hasOne(Currency::class, 'id', 'currency')->value('name') ?? '';
    }

    public function getContractAddressAttribute()
    {
        return $this->hasOne(Currency::class, 'id', 'currency')->value('contract_address') ?? '';
    }

    public function getIsChangeAttribute()
    {
        return $this->hasOne(Currency::class, 'id', 'currency')->value('is_change');
    }
    public function getIsLegalAttribute()
    {
        return $this->hasOne(Currency::class, 'id', 'currency')->value('is_legal');
    }

    public function getIsLeverAttribute()
    {
        return $this->hasOne(Currency::class, 'id', 'currency')->value('is_lever');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency', 'id');
    }

    public function currencyCoin()
    {
        return $this->belongsTo(Currency::class, 'currency', 'id');
    }
    public static function doCurl($url, $data=array(), $header=array(), $referer='', $timeout=30){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        // 模拟来源
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        $response = curl_exec($ch);
        if($error=curl_error($ch)){
            die($error);
        }
        curl_close($ch);
        return $response;
    }

    public static function makeWallet($user_id)
    {
        $currency = Currency::all();

        // 调用
        $url = '/v3/wallet/address';
        $data = [
            'form_params' => [
                'userid' => $user_id,
                'projectname' => config('app.name')
            ],
        ];
        $client = app('LbxChainServer');
        $result = $client->request('post',$url,$data);
        $result = json_decode($result);
        if ($result->code != 0) {
            return false;
        }
        $address = $result->data;
        foreach ($currency as $key => $value) {
            // 判断对应币种钱包是否已存在
            if (self::where('user_id', $user_id)->where('currency', $value->id)->exists()) {
                continue;
            }
            $userWallet = new self();
            $userWallet->user_id = $user_id;
            if ($value->type == 'btc') {
                $userWallet->address = $address->btc_address;
                $userWallet->private = $address->btc_private;
            } elseif ($value->type == 'usdt') {
                $userWallet->address = $address->usdt_address;
                $userWallet->private = $address->usdt_private;
            } elseif ($value->type == 'eth') {
                $userWallet->address = $address->eth_address;
                $userWallet->private = $address->eth_private;
            } elseif ($value->type == 'erc20') {
                $userWallet->address = $address->erc20_address;
                $userWallet->private =$address->erc20_private;
            } elseif ($value->type == 'xrp') {
                $userWallet->address = $address->xrp_address;
                $userWallet->private =$address->xrp_private;
            } else {
                continue;
            }
            $userWallet->currency = $value->id;
            $userWallet->create_time = time();
            $userWallet->save();//默认生成所有币种的钱包
        }
    }

    // public function getUsdtPriceAttribute()
    // {
    //     $last_price = 0;
    //     $currency_id = $this->attributes['currency'];
    //     $last = TransactionComplete::orderBy('id', 'desc')
    //         ->where("currency", $currency_id)
    //         ->where("legal", 1)->first();//1是pb
    //     if (!empty($last)) {
    //         $last_price = $last->price;
    //     }
    //     if ($currency_id == 1) {
    //         $last_price = 1;
    //     }
    //     return $last_price;
    // }

    public function getUsdtPriceAttribute()
    {
        $currency_id = $this->attributes['currency'];
        return Currency::getUsdtPrice($currency_id);

    }

    public function getPbPriceAttribute()
    {
        $currency_id = $this->attributes['currency'];
        return Currency::getPbPrice($currency_id);

    }

    public function getCnyPriceAttribute()
    {
        $currency_id = $this->attributes['currency'];
        return Currency::getCnyPrice($currency_id);
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function getPrivateAttribute($value)
    {
        return empty($value) ? '' : decrypt($value);
    }

    public function setPrivateAttribute($value)
    {
        $this->attributes['private'] = encrypt($value);
    }

    public function getAccountNumberAttribute($value)
    {
        return $this->user()->value('account_number') ?? '';
    }
}
