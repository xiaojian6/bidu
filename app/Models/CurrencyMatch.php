<?php

namespace App\Models;
use App\Models\Optional;

class CurrencyMatch extends Model
{
    public $timestamps = false;

    protected static $USDTRate = null;

    protected $appends = [
        'legal_name',
        'currency_name',
        'market_from_name',
        'change',
        'volume',
        'now_price',
        'fiat_convert_cny',
        'logo',
        "optional_status",
        'label_name',
        'is_show_label',
    ];

    protected static $marketFromNames = [
        '无',
        '交易所',
        '火币接口',
        '机器人',
    ];

    public function getOptionalStatusAttribute()
    {
        $legal_id = $this->attributes['legal_id'];
        $currency_id = $this->attributes['currency_id'];
        $user_id = Users::getUserId();
        if(empty($user_id)){
            $optional_status=0;
        }else{

            $optional=Optional::where("user_id",$user_id)->where("legal_id",$legal_id)->where("currency_id",$currency_id)->first();
            if(!empty($optional->legal_id)){
                $optional_status=$optional->status;
            }else{
                $optional_status=0;
            }
        }


        return  isset($optional_status) ? $optional_status : '0';
    }

    public function legal()
    {
        return $this->belongsTo(Currency::class, 'legal_id', 'id')->withDefault();
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id')->withDefault();
    }

    public function quotation()
    {
        return $this->hasOne(CurrencyQuotation::class, 'legal_id', 'legal_id');
    }

    public static function enumMarketFromNames()
    {
        return self::$marketFromNames;
    }

    public function getLogoAttribute()
    {
        return $this->currency()->value('logo') ?? '';
    }

    public function getSymbolAttribute()
    {
        return $this->getCurrencyNameAttribute() . '/' . $this->getLegalNameAttribute();
    }

    public function getMatchNameAttribute()
    {
        //return strtolower($this->getCurrencyNameAttribute() . $this->getLegalNameAttribute());
        $clone_currency_k = $this->currency()->value('clone_currency_k');
        if($clone_currency_k){
            $currency_name = $clone_currency_k;
        }else{
            $currency_name = $this->getCurrencyNameAttribute();
        }
        return strtolower($currency_name . $this->getLegalNameAttribute());
    }

    public function label()
    {
         return $this->belongsTo(Label::class, 'label_id', 'id')->withDefault();
    }

    public function getLabelNameAttribute()
    {
        return $this->label()->value('name');
    }

    public function getIsShowLabelAttribute()
    {
        return $this->label()->value('is_show_label');
    }
    public function getLegalNameAttribute()
    {
        return $this->legal()->value('name');
    }

    public function getCurrencyNameAttribute()
    {
        return $this->currency()->value('name');
    }

    public function getMarketFromNameAttribute($value)
    {
        return self::$marketFromNames[$this->attributes['market_from']];
    }

    public function getCreateTimeAttribute($value)
    {
        return $value === null ? '' : date('Y-m-d H:i:s', $value);
    }

    public function getDaymarketAttribute()
    {
        $legal_id = $this->attributes['legal_id'];
        $currency_id = $this->attributes['currency_id'];
        CurrencyQuotation::unguard();
        $quotation = CurrencyQuotation::firstOrCreate([
            'legal_id' => $legal_id,
            'currency_id' => $currency_id,
        ], [
            'match_id' => $this->attributes['id'],
            'change' => '',
            'volume' => 0,
            'now_price' => 0,
            'add_time' => time(),
        ]);
        CurrencyQuotation::reguard();
        return $quotation;
    }

    public function getChangeAttribute()
    {
        return $this->getDaymarketAttribute()->change;
    }

    public function getVolumeAttribute()
    {
        return $this->getDaymarketAttribute()->volume;
    }

    public function getNowPriceAttribute()
    {
        return $this->getDaymarketAttribute()->now_price;
    }

    public static function getHuobiMatchs()
    {
        $currency_match = self::with(['legal', 'currency'])
            ->where('market_from', 2)
            ->get();
        $huobi_symbols = HuobiSymbol::pluck('symbol')->all();
        $currency_match->transform(function ($item, $key) {
            $item->addHidden('currency');
            $item->addHidden('legal');
            $item->append('match_name');
            return $item;
        });
        //过滤掉不在火币中的交易对
        $currency_match = $currency_match->filter(function ($value, $key) use ($huobi_symbols) {
            return in_array($value->match_name, $huobi_symbols);
        });
        return $currency_match;
    }

    public function getFiatConvertCnyAttribute()
    {
        $legal_id = $this->attributes['legal_id'];
        $aa=Currency::getUSDTRate();
        $bb=Currency::getUsdtPrice($legal_id);
//        var_dump($aa);var_dump($bb);die;
//        return $legal_id;
        return bc_mul($aa,$bb);
//        return Currency::getUSDTRate();
    }
}
