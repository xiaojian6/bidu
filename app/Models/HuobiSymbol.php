<?php

namespace App\Models;

class HuobiSymbol extends Model
{
    public $timestamps = false;

    public static function getSymbolsData($symbols)
    {
        self::unguard();
        foreach ($symbols as $key => $value) {
            $huobi_symbol = new self();
            $symbols_data = [
                'base-currency' => $value['base-currency'],
                'quote-currency' => $value['quote-currency'],
                'symbol' => $value['symbol'],
            ];
            $huobi_symbol->fill($symbols_data)->save();
        }
        self::reguard();
        return true;
    }
}
