<?php

namespace App\Http\Middleware;

use App\DAO\SafeDAO;
use Closure;
use App\Models\Users;

class CheckPayPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $scene)
    {
        if (SafeDAO::checkNeedPassword($scene)) {
            $pay_password = $request->input('password', '');
            if (empty($pay_password)) {
                return response()->json([
                    'type' => 'error',
                    'message' => '请输入支付密码',
                ]);
            }
            $user_id = Users::getUserId();
            $user = Users::find($user_id);
            if (!$user) {
                return response()->json([
                    'type' => 'error',
                    'message' => '用户不存在',
                ]);
            }
            $user_pay_password = $user->pay_password;
            if (empty($user_pay_password)) {
                return response()->json([
                    'type' => 'error',
                    'message' => '您未设置支付密码',
                ]);
            }
            if (Users::MakePassword($pay_password) != $user_pay_password) {
                return response()->json([
                    'type' => 'error',
                    'message' => '支付密码不正确',
                ]);
            }
        }        
        return $next($request);
    }
}
