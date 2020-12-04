<?php

namespace App\Models;

class Currency extends Model
{
    protected $table = 'currency';

    public $timestamps = false;

    protected $appends = [
        'fiat_convert_cny',
//        'usdt_price',
    ];

    protected $hidden = [
        'contract_address',
        'key',
    ];

    protected static $USDTRate = null;

    public static function getUSDTRate()
    {
        if (is_null(self::$USDTRate)) {
            self::$USDTRate = Setting::getValueByKey('USDTRate', 6.9);
        }
        return self::$USDTRate;
    }

    /**
     * 定义一对多关系
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quotation()
    {
        return $this->hasMany(CurrencyMatch::class, 'legal_id', 'id')->orderBy('sort', 'asc')->orderBy('id', 'desc');
    }

    public function quotationlever()
    {
        return $this->hasMany(CurrencyMatch::class, 'legal_id', 'id')->where('open_lever',1)->orderBy('sort', 'asc')->orderBy('id', 'desc');
    }
    // public function getExRateAttribute()
    // {
    //     return Setting::getValueByKey('USDTRate', 6.5);
    // }

    public function getCreateTimeAttribute()
    {
        return date('Y-m-d H:i:s', $this->attributes['create_time']);
    }

    public static function getNameById($currency_id)
    {
        $currency = self::find($currency_id);
        return $currency->name;
    }

    // public function getUsdtPriceAttribute()
    // {
    //     $last_price = 1;
    //     $currency_id = $this->attributes['id'];
    //     $last = TransactionComplete::orderBy('id', 'desc')
    //         ->where("currency", $currency_id)
    //         ->where("legal", 1)->first();//1是PB
    //     if (!empty($last)) {
    //         $last_price = $last->price;
    //     }
    //     if ($currency_id == 1) {
    //         $last_price = 1;
    //     }
    //     return $last_price;
    // }

    public static function getUsdtPrice($currency_id)
    {
        $rate = 1;
        $usdt = Currency::where('name', 'USDT')->select(['name', 'id'])->first();
        $currency = Currency::find($currency_id);
        try {
            $last = MarketHour::getLastEsearchMarket($currency->name, $usdt->name);
            if (!$last) {
                throw new \Exception('未获取到行情');
            }
            $last = reset($last);
            $usdt_price = bc_mul($last['close'], $rate);
        } catch (\Exception $e) {
            //从数据库中查找
            $last = CurrencyQuotation::where('legal_id', $usdt->id)
                ->where('currency_id', $currency_id)
                ->first();
            if (!empty($last)) {
                $usdt_price = $last->now_price;
            } else {
                //从行情表中查找
                $last = MarketHour::orderBy('id', 'desc')
                    ->where("currency_id", $currency_id)
                    ->where("legal_id", $usdt->id)
                    ->first();
                if (!empty($last)) {
                    $usdt_price = bc_mul($last->highest, $rate); //行情表里面最近的数据的最高值
                } else {
                    $usdt_price = 1;//如果不存在交易对，默认为1
                }
//                $usdt_price = 1;
            }
        }
        if ($currency_id == $usdt->id) {
            $usdt_price = 1 * $rate;
        }
        return $usdt_price;
    }

    //获取币种相对于人民币的价格
    public static function getCnyPrice($currency_id)
    {
        $rate = Setting::getValueByKey('USDTRate', 6.5);
        $usdt = Currency::where('name', 'USDT')->select(['name', 'id'])->first();
        $currency = Currency::find($currency_id);
        $last = MarketHour::orderBy('id', 'desc')
            ->where("currency_id", $currency_id)
            ->where("legal_id", $usdt->id)->first();
        if (!empty($last)) {
            $cny_Price = bc_mul($last->highest, $rate); //行情表里面最近的数据的最高值
        } else {
            $last = MarketHour::getLastEsearchMarket($currency->name, $usdt->name);
            if ($last) {
                $last = reset($last);
                $cny_Price = bc_mul($last['close'], $rate);
            } else {
                $cny_Price = 1;//如果不存在交易对，默认为1
            }
        }
        if ($currency_id == $usdt->id) {
            $cny_Price = 1 * $rate;
        }
        return $cny_Price;
    }

    //获取币种相对于平台币的价格
    public static function getPbPrice($currency_id)
    {
        $usdt = Currency::where('name', UsersWallet::CURRENCY_DEFAULT)->select(['id'])->first();
        $last = MarketHour::orderBy('id', 'desc')
            ->where("currency_id", $currency_id)
            ->where("legal_id", $usdt->id)->first();
        if (!empty($last)) {
            $cny_Price = $last->highest;//行情表里面最近的数据的最高值
        } else {
            $cny_Price = 1;//如果不存在交易对，默认为1
        }
        if ($currency_id == $usdt->id) {
            $cny_Price = 1;
        }

        return $cny_Price;
    }

    public function getOriginKeyAttribute($value)
    {
        $private_key = $this->attributes['key'] ?? '';
        return $private_key != '' ? decrypt($private_key) : '';
    }

    public function getKeyAttribute($value)
    {
        return $value == '' ?: '********';
    }

    public function setKeyAttribute($value)
    {
        if ($value != '') {
            return $this->attributes['key'] = encrypt($value);
        }
    }

    public function getFiatConvertCnyAttribute()
    {

        $legal_id = $this->attributes['id'];
        $aa=Currency::getUSDTRate();
        $bb=Currency::getUsdtPrice($legal_id);
//        var_dump($aa);var_dump($bb);die;
//        return $legal_id;
        return bc_mul($aa,$bb);


//        return self::getUSDTRate();
    }
}
