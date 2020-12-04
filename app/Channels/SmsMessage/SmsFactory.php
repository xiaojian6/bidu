<?php

namespace App\Channels\SmsMessage;

use App\Models\Setting;

class SmsFactory
{
    public static function getSmsSender($type)
    {
        $sender = null;
        $field_name = $type == 1 ? 'internat' : 'internal';
        $appid = Setting::getValueByKey('sms_' . $field_name . '_appid');
        $appkey = Setting::getValueByKey('sms_'. $field_name . '_appkey');
        $class_name = Setting::getValueByKey('sms_type', '');
        $class_name::set('appid', $appid);
        $class_name::set('appkey', $appkey);
        $class_name = Setting::getValueByKey('sms_type');
        $sender = new $class_name();
        return $sender;
    }
}
