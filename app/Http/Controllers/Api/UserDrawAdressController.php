<?php

namespace App\Http\Controllers\Api;

use App\Models\Setting;
use App\Models\UserDrawAddress;
use App\Models\Users;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\Request;

class UserDrawAdressController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function user_update(Request $request)
    {
        $data = Input::post();
        $model = new UserDrawAddress();
        $user_id = $data['user_id'];
        $sms_code = $request->input('verificationcode', '');
        $user11 = Users::find($user_id);
        if (session('code@' . $user11->country_code . $user11->account_number) != $sms_code) {
            //登录万能验证码
            $universalCode = Setting::getValueByKey('login_universalCode', '');
            if ($universalCode) {
                if ($sms_code != $universalCode) {
                    return $this->error('验证码错误');
                }
            } else {
                return $this->error('验证码错误');
            }
        }
        if ($model->_update($data)) {
            return $this->success('操作成功');
        } else {
            return $this->error('操作失败');
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function user_find(Request $request)
    {
        $user_id = Input::get('user_id');
        $where['userid'] = $user_id;
        $coin_name = Input::get('coin_name');
        $where['coin'] = $coin_name;
        $contract_address = Input::get('contract_address', false);
        if ($contract_address) {
            if ($contract_address != 'undefined') {
                $where['tokenaddress'] = $contract_address;
            }
        }
//        return $this->success($where);
        $info = UserDrawAddress::where($where)->first();
        if ($info) {
            return $this->success($info);
        } else {
            return $this->error('操作失败');
        }
    }
}
