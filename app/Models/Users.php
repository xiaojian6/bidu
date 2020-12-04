<?php

/**
 * Created by PhpStorm.
 * User: swl
 * Date: 2018/7/3
 * Time: 10:23
 */

namespace App\Models;


use Illuminate\Support\Facades\DB;

class Users extends Model
{
    protected $table = 'users';
    public $timestamps = false;

    protected $hidden = [
        'password',
        'pay_password',
        'memorizing_words',
        'is_blacklist',
        'gesture_password',
    ];
    protected $appends = [
        'account',
        'uid',
        'is_seller',
        'create_date',
        'usdt',
        'caution_money',
        'parent_name',
        'my_agent_level'
    ];

    public function belongAgent()
    {
        return $this->belongsTo(Agent::class, 'agent_note_id', 'id');
    }

    public function getAgentPathAttribute()
    {
        return $this->belongAgent()->value('agent_path') ?? '';
    }

    public function getUidAttribute()
    {
        $uid_begin_value = Setting::getValueByKey('uid_begin_value', 0);
        $uid_begin_value = intval($uid_begin_value);
        $id = $this->attributes['id'] ?? 0;
        $uid = $id + $uid_begin_value;
        return $uid;
    }

    public function getUsdtAttribute()
    {
        $value = $this->attributes['id'] ?? 0;

        $us = DB::table('currency')->where('name', 'USDT')->first();

        $wal = UsersWallet::where('currency', $us->id)->where('user_id', $value)->first();

        return isset($wal->lever_balance) ? $wal->lever_balance : '0.00000';
    }

    public function getCautionMoneyAttribute()
    {
        $value = $this->attributes['id'] ?? 0;
        if ($value) {
            return DB::table('lever_transaction')->where('user_id', $value)->whereIn('status', [0, 1])->sum('caution_money');
        }
        return 0;
    }

    public function getParentNameAttribute()
    {
        $value = $this->attributes['agent_note_id'] ?? 0;
        if ($value) {
            $p = Agent::where('id', $value)->first();
            return isset($p->username) ? $p->username : '-/-';
        }
        return '-/-';
    }

    public function getMyAgentLevelAttribute()
    {
        $value = $this->attributes['agent_id'] ?? 0;
        if ($value == 0) {
            return '普通用户';
        } else {
            $m = DB::table('agent')->where('id', $value)->first();
            $name = '';
            if (empty($m)) {
                $name = '';
            } else {
                if ($m->level == 0) {
                    $name = '超管';
                } else if ($m->level > 0) {
                    $name = $m->level . '级代理商';
                }
            }

            return $name;
        }
    }

    public function getCreateDateAttribute()
    {
        $value = $this->attributes['time'] ?? 0;
        if (!empty($value)) {
            return date('Y-m-d H:i:s', $value);
        }
        return '';
    }

    //密码加密
    public static function MakePassword($password, $type = 0)
    {
        if ($type == 0) {
            $salt = 'ABCDEFG';
            $passwordChars = str_split($password);
            foreach ($passwordChars as $char) {
                $salt .= md5($char);
            }
        } else {
            $salt = 'TPSHOP' . $password;
        }
        return md5($salt);
    }

    public static function getByAccountNumber($account_number)
    {
        return self::where('account_number', $account_number)->first();
    }

    public static function getByString($string, $country_code = '')
    {
        if (empty($string)) {
            return "";
        }
        return self::where(function ($query) use ($string) {
            $query->where("phone", $string)
                ->orwhere('email', $string)
                ->orWhere('account_number', $string);
        })
            ->when($country_code != '', function ($query) use ($country_code) {
                $query->where('country_code', $country_code);
            })
            ->first();
    }

    public static function getById($id)
    {
        if (empty($id)) {
            return "";
        }
        return self::where("id", $id)->first();
    }

    //生成邀请码
    public static function getExtensionCode()
    {
        $code = self::generate_password(4);
        if (self::where("extension_code", $code)->first()) {
            //如果生成的邀请码存在，继续生成，直到不存在
            $code = self::getExtensionCode();
        }
        return $code;
    }

    public static function generate_password($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $password = "";
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $password;
    }

    public static function getUserId()
    {
        // return session('user_id');
        $token = Token::getToken();
        $user_id = Token::getUserIdByToken($token);
        return $user_id;
    }

    public function getTimeAttribute()
    {
        if ($this->attributes['time'] ?? 0) {
            $value = $this->attributes['time'];
            return $value ? date('Y-m-d H:i:s', $value) : '';
        } else {
            return "";
        }
    }

    //获取用户的账号  手机号或邮箱
    public function getAccountAttribute()
    {
        $value = $this->attributes['phone'] ?? '';
        if (empty($value)) {
            $value = $this->getAttribute('email');
            if ($value) {
                $n = strripos($value, '@');
                $value = mb_substr($value, 0, 2) . '******' . mb_substr($value, $n);
            } else {
                $value = '';
            }
        } else {
            $value = mb_substr($value, 0, 3) . '******' . mb_substr($value, -3, 3);
        }
        return $value;
    }

    public function getIsSellerAttribute()
    {
        $user_id = $this->attributes['id'] ?? 0;
        if ($user_id) {
            $seller = Seller::where('user_id', $user_id)->first();
            if (!empty($seller)) {
                return 1;
            }
        }
        return 0;
    }

    public function cashinfo()
    {
        return $this->belongsTo(UserCashInfo::class, 'id', 'user_id');
    }

    public function legalDeal()
    {
        return $this->hasOne(C2cDeal::class, 'seller_id', 'id');
    }
}
