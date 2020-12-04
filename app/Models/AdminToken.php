<?php

namespace App\Models;


use Illuminate\Support\Carbon;

class AdminToken extends Model
{
    protected $table = 'admin_token';
    protected $dateFormat = 'U';

    //设置token
    public static function setToken($admin_id)
    {
        $token = new self();
        $token_str = md5($admin_id . time() . mt_rand(0, 99999));

        $token->admin_id = $admin_id;
        $token->token = $token_str;
        $token->expired_at = Carbon::tomorrow()->endOfDay()->getTimestamp();
        
        return $token->save() ? $token_str : false;
    }

    public static function getToken($admin_id)
    {
        $admin_token = self::where('admin_id', $admin_id)
            ->where('expired_at', '>=', time())
            ->firstOrNew([]);
        return $admin_token->token ?? '';
    }
}
