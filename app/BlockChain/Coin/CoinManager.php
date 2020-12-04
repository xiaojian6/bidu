<?php

namespace App\BlockChain\Coin;

use Illuminate\Support\Facades\App;

class CoinManager
{
    public static function resolve($name, $decimal_scale = 0, $contract_token = '', $project_name = '', $api_base_url = '')
    {
        $class_name = '\\App\\BlockChain\\Coin\\Driver\\' . $name;
        return new $class_name($decimal_scale, $contract_token, $project_name, $api_base_url);
    }
}