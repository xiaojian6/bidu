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
            $to = '+' . $country_code . $phone;
            $area = 1;
        }
        $url = $this->getGetwayUrl($area);
        list(
            'appid' => $appid,
            'appkey' => $appkey,
        ) = $this->getSmsKey();
        $client = new Client();
        $response = $client->post($url, [
            'form_params' => [
                'appid' => $appid,
                'to' => $to,
                'project' => $content,
                'signature' => $appkey,
                'vars' => $params,
            ],
        ]);
        $result = $response->getBody()->getContents();
        $result = json_decode($result);
        //return $result;
        if (isset($result->status) && $result->status == 'success') {
            return true;
        }
        throw new \Exception("发送失败:{$result->msg}", $result->code);
        //通知失败处理逻辑
        return false;
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
        $internal_url = 'https://api.mysubmail.com/message/xsend'; //国内网关
        $internat_url = 'https://api.mysubmail.com/internationalsms/xsend'; //国际网关
        return $area == 0 ? $internal_url : $internat_url;
    }
}