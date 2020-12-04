<?php

namespace App\Channels;

use App\Models\SmsProject;
use Illuminate\Notifications\Notification;
use App\Channels\SmsMessage\Submail;
use App\Channels\SmsMessage\SmsFactory;

class Sms
{

    public function __construct()
    {
    }

    public function send($notifiable, Notification $notification)
    {
        try {
            list(
                'phone' => $phone,
                'content' => $content,
                'params' => $params,
                'country_code' => $country_code
            ) = $notification->toSms($notifiable);
            $sender = SmsFactory::getSmsSender($country_code == 86 ? 0 : 1);
            $send_result = $sender->send($phone, $content, $params, $country_code);
            if (!$send_result) {
                //发送失败处理逻辑
                throw new \Exception('发送失败');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
