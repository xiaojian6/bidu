<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\AdminRolePermission;
use App\Models\Users;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Input;
use Symfony\Component\Process\Process;
use App\Models\AdminToken;
use App\Http\Controllers\Admin\SmsController;

class DefaultController extends Controller{

    public function salt(Request $request)
    {
        $password = $request->get("passwd");
        $password = Users::MakePassword($password);
        return $password;
    }
    public function login()
    {

        $username = Input::get('username','');
        $password = Input::get('password','');
        if (empty($username)){
            return $this->error('用户名必须填写');
        }
        if (empty($password)){
            return $this->error('密码必须填写');
        }
        $password = Users::MakePassword($password);
        $admin = Admin::where('username',$username)->where('password', $password)->first();
        if (empty($admin)){
            return $this->error('用户名密码错误');
        } else {
            $role = AdminRole::find($admin->role_id);
            if (empty($role)){
                return $this->error('账号异常');
            }else{
                session()->put('admin_username', $admin->username);
                session()->put('admin_id', $admin->id);
                session()->put('admin_role_id', $admin->role_id);
                session()->put('admin_is_super', $role->is_super);
                AdminToken::setToken($admin->id);
                return $this->success('登陆成功');
            }
        }
    }

    public function login1()
    {
        return view('admin.login1');
    }

    public function index()
    {
        $admin_role = AdminRolePermission::where("role_id",session()->get('admin_role_id'))->get();
        $admin_role_data = array();
        foreach ($admin_role as $r){
            array_push($admin_role_data,$r->action);
        }
        return view('admin.indexnew')->with("admin_role_data",$admin_role_data);;
    }

    public function indexnew()
    {
        $admin_role = AdminRolePermission::where("role_id",session()->get('admin_role_id'))->get();
        $admin_role_data = array();
        foreach ($admin_role as $r){
            array_push($admin_role_data,$r->action);
        }
        $admin_id = session()->get('admin_id');
        $admin = Admin::find($admin_id);
        return view('admin.index')
            ->with("admin_role_data",$admin_role_data)
            ->with('admin', $admin);
    }

    public function getVerificationCode(Request $request)
    {
        $type = $request->input('type', 1);
        $data = [
            'projectname' =>config('app.name'),
            'type' => $type,
        ];
        $sms = new SmsController();
        $res = $sms->sendMail();

        if ($res['code']){
            return $this->success('发送成功');
        }
        return $this->error('发送失败');
    }
}
