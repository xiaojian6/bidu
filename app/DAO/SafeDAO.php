<?php

namespace App\DAO;

use App\Models\Setting;

class SafeDAO
{
    public static function checkNeedPassword($scene)
    {
        $key = "{$scene}_need_password";
        return $value = Setting::getValueByKey($key, 1);
    }
}
