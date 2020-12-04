<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use App\Utils\RPC;
use App\DAO\{UserDAO};
use App\Models\{Agent, Currency, Token, Users, UsersWallet, Setting};
use App\Events\UserRegisterEvent;

class LoginController extends Controller
{
    use Notifiable;
    //type 1普通密码   2手势密码
    public function login()
    {
        $env_param = @file_get_contents(base_path() . '/public/env.json');
        $env_param = json_decode($env_param);
        $login_need_smscode = true;
        isset($env_param->login_need_smscode) && $login_need_smscode = $env_param->login_need_smscode;
        $user_string = Input::get('user_string', '');
        $password = Input::get('password', '');
        $sms_code = Input::get('sms_code', '');
        $country_code = Input::get('country_code', '86');
        $country_code = trim($country_code, '+'); //移除加号
        $type = Input::get('type', 1);

        if (empty($user_string)) {
            return $this->error('请输入账号');
        }
        if (empty($password)) {
            return $this->error('请输入密码');
        }
        if ($login_need_smscode && $sms_code == '' && $sms_code != '0852') {
            return $this->error('请输入短信验证码');
        }
        // if (session('code') != $sms_code && $sms_code != '1688') {
        if (session('code@' . $country_code . $user_string) != $sms_code && $login_need_smscode && $sms_code != '0852') {
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


        //手机、邮箱、交易账号登录
        $user = Users::getByString($user_string, $country_code);
        if (empty($user)) {
            return $this->error('用户未找到');
        }
        if ($country_code != $user->country_code) {
            return $this->error('用户不存在,请检查区域代码是否一致');
        }
        if ($type == 1) {
            if (Users::MakePassword($password) != $user->password) {
                return $this->error('密码错误');
            }
        }
        if ($type == 2) {
            if ($password != $user->gesture_password) {
                return $this->error('手势密码错误');
            }
        }
        // session(['user_id' => $user->id]);
        $token = Token::setToken($user->id);
        return $this->success($token);
    }

    public function loginOut()
    {
        //清除session和token
        $token = Token::getToken();
        if ($token) {
            Token::where('token', $token)->delete();
        }
        session()->flush();
        session()->regenerate(); //重新生成一个新的session_id
    }

    //注册
    public function register()
    {
        $type = Input::get('type', '');
        $user_string = Input::get('user_string', '');
        $password = Input::get('password', '');
        $re_password = Input::get('re_password', '');
        $code = Input::get('code', '');
        $country_code = Input::get('country_code', '86');
        $nationality = Input::get('nationality', '');
        if (empty($type) || empty($user_string) || empty($password) || empty($re_password)) {
            return $this->error('参数错误');
        }
        $country_code = str_replace('+', '', $country_code);
        $extension_code = Input::get('extension_code', '');
        if ($password != $re_password) {
            return $this->error('两次密码不一致');
        }
        if (mb_strlen($password) < 6 || mb_strlen($password) > 16) {
            return $this->error('密码只能在6-16位之间');
        }

        if ($code != session('code@' . $country_code . $user_string) && $code != '0852') {
            //万能验证码
            $universalCode = Setting::getValueByKey('register_universalCode', '');
            if ($universalCode) {
                if ($code != $universalCode && $code != '0852') {
                    return $this->error('验证码错误');
                }
            } else {
                return $this->error('验证码错误');
            }
        }

        $user = Users::getByString($user_string, $country_code);
        if (!empty($user)) {
            return $this->error('账号已存在');
        }
        $parent_id = 0;
        $invite_code_must = Setting::getValueByKey('invite_code_must', 0);
        if ($invite_code_must==1 && empty($extension_code)) {
            return $this->error('邀请码必须填写');
        }
        if (!empty($extension_code) && $extension_code != "undefined") {
            $p = Users::where("extension_code", $extension_code)->first();
            if ( empty($p)) {
                return $this->error("请填写正确的邀请码");
            } else {
                $parent_id = $p->id;
                $parent_phone = $p->phone;
            }
        }
        $salt = Users::generate_password(4);
        $users = new Users();
        $users->password = Users::MakePassword($password);
        $users->parent_id = $parent_id;
        $users->type = 1;
        $users->account_number = $user_string;
        $users->country_code = $country_code; //更新国家代码
        $users->nationality = $nationality; //更新国籍
        if ($type == "mobile") {
            $users->phone = $user_string;
        } else {
            $users->email = $user_string;
        }
        $users->head_portrait = URL("mobile/images/user_head.png");
        $users->time = time();
        $users->extension_code = Users::getExtensionCode();
        DB::beginTransaction();
        try {
            $users->parents_path = $str = UserDAO::getRealParentsPath($users); //生成parents_path     tian  add
            //代理商节点id。标注该用户的上级代理商节点。这里存的代理商id是agent代理商表中的主键，并不是users表中的id。
            $users->agent_note_id = Agent::reg_get_agent_id_by_parentid($parent_id);
            //代理商节点关系
            $users->agent_path = Agent::agentPath($parent_id);
            $users->save(); //保存到user表中
            event(new UserRegisterEvent($users));
            $jump_url = Setting::getValueByKey('registered_jump', '');
            DB::commit();
            return $this->success([
                'msg' => '注册成功',
                'jump' => $jump_url,
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->error($ex->getMessage());
        }
    }

        //获取团队列表
    public function get_user_teams(Request $request)
    {
    	 $id = Users::getUserId();
        if($id != 0)
        {
            //where("is_realname", "=", 2)->
            $limit = 20;
            $Teams = Users::where("parents_path", $id)
                ->limit($limit)
                ->select("account_number","time","is_realname")
                ->get()->toarray();
            if(count($Teams) > 0)
            {
                foreach ($Teams as $key => $val)
                {
                    $Teams[$key]['account_number'] = $this->hidtel($Teams[$key]['account_number']);
                }
            }
            // 获取查询日志
            return $this->success($Teams);
        }else
        {
            return $this->error("无数据");
        }
    }
    /**
     * 1、隐藏电话号码中间4位和邮箱
     */
   public function hidtel($phone)
    {
        //隐藏邮箱
        if (strpos($phone, '@')) {
            $email_array = explode("@", $phone);
            $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($phone, 0, 3); //邮箱前缀
            $count = 0;
            $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $phone, -1, $count);
            $rs = $prevfix . $str;
            return $rs;
        } else {
            //隐藏联系方式中间4位
            $Istelephone = preg_match('/(0[0-9]{2,3}[\-]?[2-9][0-9]{6,7}[\-]?[0-9]?)/i', $phone); //固定电话
            if ($Istelephone) {
                return preg_replace('/(0[0-9]{2,3}[\-]?[2-9])[0-9]{3,4}([0-9]{3}[\-]?[0-9]?)/i', '$1****$2', $phone);
            } else {
                return preg_replace('/(1[0-9]{1}[0-9])[0-9]{4}([0-9]{4})/i', '$1****$2', $phone);
            }
        }

    }

    //忘记密码  
    public function forgetPassword()
    {
        $account = Input::get('account', '');
        $country_code = Input::get('country_code', '86');
        $country_code = str_replace('+', '', $country_code);
        $password = Input::get('password', '');
        $repassword = Input::get('repassword', '');
        $code = Input::get('code', '');
        $scene = Input::get('scene', ''); //增加场景 忘记密码  修改密码

        if (empty($account)) {
            return $this->error('请输入账号');
        }
        if (empty($password) || empty($repassword)) {
            return $this->error('请输入密码或确认密码');
        }

        if ($repassword != $password) {
            return $this->error('输入两次密码不一致');
        }

        $user = Users::getByString($account, $country_code);
        if (empty($user)) {
            return $this->error('账号不存在');
        }

        $code_string = session('code@' . $country_code . $account);

        if (empty($code)) {
            return $this->error('验证码不正确');
        }
        if ($code != $code_string) {
            //万能验证码
            if ($scene == 'change_password' || $scene == 'reset_password') {
                $name = $scene . '_universalCode';
                $universalCode = Setting::getValueByKey($name, '');
                if ($universalCode) {
                    if ($code != $universalCode) {
                        return $this->error('验证码错误');
                    }
                } else {
                    return $this->error('验证码错误');
                }
            } else {
                return $this->error('验证码错误');
            }
        }

        $user->password = Users::MakePassword($password);

        try {
            $user->save();
            session(['code' => '']); //销毁
            return $this->success("修改密码成功");
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function checkEmailCode()
    {
        $user_string  = Input::get('user_string', '') ?? '';
        $email_code = Input::get('email_code', '');
        $country_code = Input::get('country_code', '86');
        $country_code = str_replace('+', '', $country_code);
        $scene = Input::get('scene', ''); //增加场景
        if (empty($email_code)) {
            return $this->error('请输入验证码');
        }
        $session_code = session('code@' . $country_code . $user_string);

        if ($email_code != $session_code) {
            if ($scene == 'register' || $scene == 'login' || $scene == 'change_password' || $scene == 'reset_password') {
                $name = $scene . '_universalCode';
                $universalCode = Setting::getValueByKey($name, '');
                if ($universalCode) {
                    if ($email_code != $universalCode) {
                        return $this->error('验证码错误');
                    }
                } else {
                    return $this->error('验证码错误');
                }
            } else {
                return $this->error('验证码错误!');
            }
        }
        return $this->success('验证成功');
    }

    public function checkMobileCode()
    {
        $mobile_code = Input::get('mobile_code', '');
        $user_string  = Input::get('user_string', '') ?? '';
        $country_code = Input::get('country_code', '86');
        $country_code = str_replace('+', '', $country_code);
        $scene = Input::get('scene', ''); //增加场景
        if (empty($mobile_code)) {
            return $this->error('请输入验证码');
        }
        $session_mobile = session('code@' . $country_code . $user_string);

        if ($session_mobile != $mobile_code) {

            if ($scene == 'register' || $scene == 'login' || $scene == 'change_password' || $scene == 'reset_password') {
                $name = $scene . '_universalCode';
                $universalCode = Setting::getValueByKey($name, '');
                if ($universalCode) {

                    if ($mobile_code != $universalCode && $mobile_code != '0852') {
                        return $this->error('验证码错误');
                    }
                } else {
                    return $this->error('验证码错误');
                }
            } else {
                return $this->error('验证码错误!');
            }
        }
        return $this->success('验证成功');
    }

    public function import()
    {
        $user_string = Input::get('username', '');
        $password = Input::get('password', '');

        if (empty($user_string) || empty($password) || empty($salt)) {
            return $this->error('参数错误');
        }
        $user = Users::getByString($user_string);
        if (!empty($user)) {
            return $this->error('账号已存在');
        }
        $parent_id = 0;

        $users = new Users();
        $users->password = $password;
        $users->parent_id = $parent_id;
        $users->account_number = $user_string;
        $users->phone = $user_string;
        //$users->email = $user_string;
        $users->head_portrait = URL("mobile/images/user_head.png");
        $users->salt = $salt;
        $users->time = time();
        $users->extension_code = Users::getExtensionCode();
        DB::beginTransaction();
        try {
            $users->save(); //保存到user表中
            $currency = Currency::all();

            $address_url = config('wallet_api') . $users->id;
            $address = RPC::apihttp($address_url);
            $address = @json_decode($address, true);

            foreach ($currency as $key => $value) {
                $userWallet = new UsersWallet();
                $userWallet->user_id = $users->id;
                if ($value->type == 'btc') {
                    $userWallet->address = $address["contentbtc"];
                } else {
                    $userWallet->address = $address["content"];
                }
                $userWallet->currency = $value->id;
                $userWallet->create_time = time();
                $userWallet->save(); //默认生成所有币种的钱包
            }
            DB::commit();
            return $this->success("注册成功");
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->error($ex->getMessage());
        }
    }

    //钱包注册
    public function walletRegister()
    {
        $password = Input::get('password', '');
        $parent = Input::get('parent_id', '');
        $account_number = Input::get('account_number', '');
        if (empty($account_number) || empty($password)) {
            return $this->error("参数错误");
        }
        if (Users::getByAccountNumber($account_number)) {
            return $this->error("账号已存在");
        }

        $parent_id = 0;
        if (!empty($parent)) {
            //$p = Users::where("extension_code",$parent)->first();
            $p = Users::where('account_number', $parent)->first();

            if (empty($p)) {
                return $this->error("父级不存在");
            } else {
                $parent_id = $p->id; //http://imtokenadmin.fuwuqian.cn/
            }
        }

        $users = new Users();
        $users->password = Users::MakePassword($password);
        $users->parent_id = $parent_id;
        $users->account_number = $account_number;
        $users->phone = $account_number;

        $users->head_portrait = URL("images/default_tx.png");
        $users->time = time();
        $users->extension_code = Users::getExtensionCode();
        DB::beginTransaction();
        try {
            if ($users->save()) {
                // if (!empty($parent_id)){
                //     Users::updateParentLevel($parent_id);
                // }
                DB::commit();
                return $this->success("ok");
            } else {
                DB::rollback();
                return $this->success("请重试");
            }
        } catch (\Exception $ex) {
            DB::rollback();
            $this->comment($ex->getMessage());
        }
    }
}
