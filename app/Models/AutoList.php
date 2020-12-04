<?php

namespace App\Models;

use App\Models\Currency;

class AutoList extends Model
{
    protected $table = 'auto_list';
    public $timestamps = false;

    protected $appends = [
        'sell_account',
        'buy_account',
        'currency_name',
        'legal_name'
    ];

    public function getBuyAccountAttribute()
    {
        return $this->hasOne(Users::class, 'id', 'buy_user_id')->value('account_number') ?? '';
    }

    public function getSellAccountAttribute()
    {
        return $this->hasOne(Users::class, 'id', 'sell_user_id')->value('account_number') ?? '';
    }

    public function getCurrencyNameAttribute()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id')->value('name');
    }

    public function getLegalNameAttribute()
    {
        return $this->hasOne(Currency::class, 'id', 'legal_id')->value('name');
    }

    public function getCreateTimeAttribute()
    {
        return date('Y-m-d H:i:s', $this->attributes['create_time']);
    }

    public static function getPriceArea($currency_id, $legal_id)
    {
        $results = array();
        //买入最高价
        $in = TransactionIn::where('currency', $currency_id)->where('legal', $legal_id)->where('number', '>', 0)->orderBy('price', 'desc')->first();
        //卖出最低价
        $out = TransactionOut::where('currency', $currency_id)->where('legal', $legal_id)->where('number', '>', 0)->orderBy('price', 'asc')->first();
        if (!empty($in) && !empty($out)) {
            $results['min'] = $in->price;
            $results['max'] = $out->price;
        }
        return $results;
    }
}
