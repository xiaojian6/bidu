<?php

/**
 * Created by PhpStorm.
 * User: swl
 * Date: 2018/7/3
 * Time: 10:23
 */

namespace App\Models;


class UserDrawAddress extends Model
{
    protected $connection = 'mysql_middle';
    protected $table = "user_drawaddress";
    const UPDATED_AT = 'update_time';

    public function _update($data)
    {
        $where['userid'] = $data['user_id'];
        $where['coin'] = $data['coin_name'];
        $save['userid'] = $data['user_id'];
        $save['coin'] = $data['coin_name'];
        $save['address'] = $data['address'];
        if (isset($data['contract_address'])) {
            if ($data['contract_address'] != 'undefined') {
                $save['tokenaddress'] = $data['contract_address'];
            }
        }
        $info = UserDrawAddress::where($where)->first();
        if ($info) {
            return UserDrawAddress::where($where)->update($save);
        } else {
            return UserDrawAddress::insert($save);
        }
    }
}