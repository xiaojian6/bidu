<?php

/**
 * Created by PhpStorm.
 * User: wyl
 * Date: 2020/02/29
 * Time: 10:37
 */

namespace App\Models;

use Illuminate\Support\Facades\DB;

class AirdropConfig extends Model
{
    public static function get_air_config()
    {
        $lists = DB::table('airdrop_config')->where("config","airdrop")->first();
        if($lists)
        {
            $lists = AirdropConfig::objToArr($lists);
            $lists['stime'] = date("Y-m-d H:i:s",$lists['stime']);
            $lists['endTime'] = date("Y-m-d H:i:s",$lists['endTime']);
        }
        return $lists;
    }
    public static function objToArr($object) {
        //先编码成json字符串，再解码成数组
        return json_decode(json_encode($object), true);
    }
    public static function get_locked_scale()
    {
        $lists = DB::table('lock_scale')->where("config","locked_release")->first();
        if($lists)
        {
            $lists = AirdropConfig::objToArr($lists);
        }
        return $lists;
    }
    //改变空投活动状态
    public static function chage_status($status = 0)
    {
        $sta['status'] = $status;
        DB::table('airdrop_config')->where("config","airdrop")->update($sta);
    }
}
