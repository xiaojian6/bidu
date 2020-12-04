<?php

namespace App\Models;

class UserMatch extends Model
{
    protected $table = 'user_match';

    public $timestamps = false;

    protected $appends = [
        'legal_id',
        'legal_name',
        'currency_id',
        'currency_name',
    ];

    public function currencyMatch()
    {
        return $this->belongsTo(CurrencyMatch::class, 'currency_match_id', 'id')->withDefault();
    }

    public function getLegalIdAttribute()
    {
        return $this->currencyMatch()->getResults()->getAttribute('legal_id');
    }

    public function getCurrencyIdAttribute()
    {
        return $this->currencyMatch()->getResults()->getAttribute('currency_id');
    }

    public function getLegalNameAttribute()
    {
        return $this->currencyMatch()->getResults()->getAttribute('legal_name');
    }

    public function getCurrencyNameAttribute()
    {
        return $this->currencyMatch()->getResults()->getAttribute('currency_name');
    }
}
