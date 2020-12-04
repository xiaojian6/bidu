<?php

namespace App\Channels\SmsMessage;

interface SmsInterface
{
    /**
     * 设置依赖参数
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value);

    /**
     * 连结(可用于检测所需参数是否完整)
     *
     * @return bool
     */
    public function connect();

    /**
     * 发送短信
     *
     * @param string $phone
     * @param string $content
     * @param mixed $params
     * @param string $country_code
     * @return bool
     */
    public function send($phone, $content, $params, $country_code);
}
