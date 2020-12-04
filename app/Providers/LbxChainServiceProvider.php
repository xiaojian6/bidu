<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

class LbxChainServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('LbxChainServer', function ($app) {
            return new curl();
        });
    }
}
class curl
{
    public function request($method,$url, $data=array()){
        $url = config('lbxchain.wallet_api') . $url;
        if($method === 'post'){
            return $this->post($url, $data);
        }
    }

    public function post($url, $data=array(), $referer='127.0.0.1', $timeout=30){

        $data = $data['form_params'];
        // 设置IP
        $header = array(
            'CLIENT-IP: 127.0.0.1',
            'X-FORWARDED-FOR: 127.0.0.1'
        );
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
}
