<?php
/**
 * Created by PhpStorm.
 * User: john
 * Date: 2016/2/17
 * Time: 11:37
 */

namespace App\Utils;

use Illuminate\Support\Facades\Config;

class RPC
{
    public static function apihttp($url, $method='GET', $data=null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if($method != 'GET' && $data != null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        $info = curl_exec($ch);
        curl_close($ch);

        $jsonInfo = @json_decode($info, true);
        if(empty($jsonInfo)) {
            \Log::alert($info);
        }

        return $info;
    }

    public static function http_post($url, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
}