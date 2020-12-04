<?php

namespace App\Channels\SmsMessage;

use GuzzleHttp\Client;

class Submail implements SmsInterface
{
    protected static $config;

    public static function set($key, $value)
    {
        self::$config[$key] = $value;
    }

    public function connect()
    {
        if (!isset(self::$config['appid']) || !isset(self::$config['appkey'])) {
            return false;
        }
        return true;
    }

    public function send($phone, $content, $params, $country_code = 86)
    {
        if (!$this->connect()) {
            return false;
        }
        if ($country_code == '86') {
            $to = $phone;
            $area = 0;
        } else {
            $to = $country_code . $phone;
            $area = 1;
        }
        $url = $this->getGetwayUrl($area);
        $TemplateId = $this->getTemplateId($area);
        $SignId = $this->getSignId($area);
//        list(
//            'appid' => $appid,
//            'appkey' => $appkey,
//        ) = $this->getSmsKey();

        $data =[
            'Account' => '15659181230',
            'Pwd' => '5110856da70dee182cd017b3d',
            'Content' => $params['code'],
            'Mobile' => $to,
            'TemplateId' => $TemplateId,
            'SignId' => $SignId,
        ];
        $result = $this->v_post($url,$data);

        $result = json_decode($result);
        //return $result;
        if (isset($result->Code) && $result->Code == '0') {
            return true;
        }
        throw new \Exception("发送失败:{$result->msg}", $result->code);
        //通知失败处理逻辑
        return false;
    }


    protected  function v_post($url, $data, $proxy = null, $timeout = 20) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); //在HTTP请求中包含一个"User-Agent: "头的字符串。
        curl_setopt($curl, CURLOPT_HEADER, 0); //启用时会将头文件的信息作为数据流输出。
        curl_setopt($curl, CURLOPT_POST, true); //发送一个常规的Post请求
        curl_setopt($curl,  CURLOPT_POSTFIELDS, $data);//Post提交的数据包
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); //启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //文件流形式
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); //设置cURL允许执行的最长秒数。
        $content = curl_exec($curl);
        curl_close($curl);
        unset($curl);
        return $content;
    }

    protected function getSmsKey()
    {
        return [
            'appid' => self::$config['appid'] ?? '',
            'appkey' => self::$config['appkey'] ?? '',
        ];
    }

    protected function getGetwayUrl($area = 0)
    {
        $internal_url = 'http://api.feige.ee/SmsService/Template'; //国内网关
        $internat_url = 'http://api.feige.ee/SmsService/Inter'; //国际网关
        return $area == 0 ? $internal_url : $internat_url;
    }

    protected function getTemplateId($area = 0)
    {
        $internal_url = '173918'; //国内网关
        $internat_url = '53546'; //国际网关
        return $area == 0 ? $internal_url : $internat_url;
    }

    protected function getSignId($area = 0)
    {
        $internal_url = '343937'; //国内网关
        $internat_url = '48280'; //国际网关
        return $area == 0 ? $internal_url : $internat_url;
    }
}
