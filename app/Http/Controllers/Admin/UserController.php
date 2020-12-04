<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\{Address, AccountLog, Users, UserCashInfo, UserReal, UsersWallet};

class UserController extends Controller
{
    public function index()
    {
        return view("admin.user.index");
    }

    //导出用户列表至excel
    public function csv()
    {
        $data = Users::all()->toArray();
        return Excel::create('用户数据', function ($excel) use ($data) {
            $excel->sheet('用户数据', function ($sheet) use ($data) {
                $sheet->cell('A1', function ($cell) {
                    $cell->setValue('ID');
                });
                $sheet->cell('B1', function ($cell) {
                    $cell->setValue('账户名');
                });
                $sheet->cell('C1', function ($cell) {
                    $cell->setValue('电话');
                });
                $sheet->cell('D1', function ($cell) {
                    $cell->setValue('邮箱');
                });
                $sheet->cell('E1', function ($cell) {
                    $cell->setValue('父级ID');
                });
                $sheet->cell('F1', function ($cell) {
                    $cell->setValue('邀请码');
                });
                $sheet->cell('G1', function ($cell) {
                    $cell->setValue('用户状态');
                });
                $sheet->cell('H1', function ($cell) {
                    $cell->setValue('头像');
                });
                $sheet->cell('I1', function ($cell) {
                    $cell->setValue('注册时间');
                });
                if (!empty($data)) {
                    foreach ($data as $key => $value) {
                        $i = $key + 2;
                        $sheet->cell('A' . $i, $value['id']);
                        $sheet->cell('B' . $i, $value['account_number']);
                        $sheet->cell('C' . $i, $value['phone']);
                        $sheet->cell('D' . $i, $value['email']);
                        $sheet->cell('E' . $i, $value['parent_id']);
                        $sheet->cell('F' . $i, $value['extension_code']);
                        $sheet->cell('G' . $i, $value['status']);
                        $sheet->cell('H' . $i, $value['head_portrait']);
                        $sheet->cell('I' . $i, $value['time']);
                    }
                }
            });
        })->download('xlsx');
    }

    //用户列表
    public function lists(Request $request)
    {
        $limit = $request->get('limit', 10);
        $account = $request->get('account', '');

        $list = new Users();
        $list = $list->leftjoin("user_real", "users.id", "=", "user_real.user_id");
        //var_dump($n);die;
        if (!empty($account)) {
            $list = $list->where("phone", 'like', '%' . $account . '%')
                ->orwhere('email', 'like', '%' . $account . '%')
                ->orWhere('account_number', 'like', '%' . $account . '%');
        }
        $list = $list->select("users.*", "user_real.card_id")->orderBy('users.id', 'desc')->paginate($limit);
        return response()->json(['code' => 0, 'data' => $list->items(), 'count' => $list->total()]);
    }

    public function edit(Request $request)
    {
        $id = $request->get('id', 0);
        if (empty($id)) {
            return $this->error("参数错误");
        }
        $result = Users::leftjoin("user_real", "users.id", "=", "user_real.user_id")
            ->select("users.*", "user_real.card_id", "user_real.name")
            ->findOrFail($id);
        $cashinfo = UserCashInfo::unguarded(function () use ($id) {
                return UserCashInfo::firstOrNew(['user_id' => $id]);
            });
        
        return view('admin.user.edit', [
            'result' => $result,
            'cashinfo' => $cashinfo,
        ]);
    }

    //编辑用户信息  
    public function doedit()
    {
        // $phone = Input::get("phone");
        // $email = Input::get("email");
        $id = Input::get("id");
        $name = Input::get("name", '');
        $card_id = Input::get("card_id", '');
        $password = Input::get("password", '');
        $account_number = Input::get("account_number", '');
        $pay_password = Input::get("pay_password", '');
        $bank_account = Input::get("bank_account", '');
        $bank_name = Input::get("bank_name", '');
        $alipay_account = Input::get("alipay_account", '');
        $wechat_nickname = Input::get("wechat_nickname", '');
        $wechat_account = Input::get("wechat_account", '');
        $wechat_collect = Input::get("wechat_collect", '');
        $alipay_collect = Input::get("alipay_collect", '');
        
        if (empty($id)) {
            return $this->error("参数错误");
        }

        try {
            DB::beginTransaction();
            // 用户账号
            $user = Users::findOrFail($id);
            $user->account_number = $account_number;
            $password != '' && $user->password = Users::MakePassword($password);
            $pay_password != '' && $user->pay_password = Users::MakePassword($pay_password);
            $user->save();
            // 收款信息
            $cashinfo = UserCashInfo::unguarded(function () use ($id) {
                return UserCashInfo::firstOrNew(['user_id' => $id]);
            });
            $bank_name != '' && $cashinfo->bank_name = $bank_name;
            $bank_account != '' && $cashinfo->bank_account = $bank_account;
            $alipay_account != '' && $cashinfo->alipay_account = $alipay_account;
            $alipay_collect != '' && $cashinfo->alipay_collect = $alipay_collect;
            $wechat_account != '' && $cashinfo->wechat_account = $wechat_account;
            $wechat_nickname != '' && $cashinfo->wechat_nickname = $wechat_nickname;
            $wechat_collect != '' && $cashinfo->wechat_collect = $wechat_collect;
            $cashinfo->save();
            // 实名信息
            $real = UserReal::unguarded(function () use ($id) {
               return UserReal::firstOrNew(['user_id' => $id], ['review_status' => 1]);
            });
            $name != '' && $real->name = $name;
            $card_id != '' && $real->card_id = $card_id;
            $real->save();
            DB::commit();
            return $this->success('编辑成功');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->error($ex->getMessage());
        }
    }

    public function del(Request $request)
    {
        return $this->error('禁止删除用户,将会造成系统崩溃');
        $id = $request->get('id');
        $user = Users::getById($id);
        if (empty($user)) {
            $this->error("用户未找到");
        }
        try {
            $user->delete();
            return $this->success('删除成功');
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function lock(Request $request)
    {
        $id = $request->get('id', 0);

        $user = Users::find($id);
        if (empty($user)) {
            return $this->error('参数错误');
        }
        if ($user->status == 1) {
            $user->status = 0;
        } else {
            $user->status = 1;
        }
        try {
            $user->save();
            return $this->success('操作成功');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }

    public function allowExchange(Request $request)
    {
        $id = $request->get('id', 0);
        $user = Users::find($id);
        if (empty($user)) {
            return $this->error('参数错误');
        }
        try {
            $user->type = 1 - $user->type;
            $user->save();
            return $this->success('操作成功');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }

    public function wallet(Request $request)
    {
        $id = $request->get('id', null);
        if (empty($id)) {
            return $this->error('参数错误');
        }
        return view("admin.user.user_wallet", ['user_id' => $id]);
    }

    public function walletList(Request $request)
    {
        $limit = $request->get('limit', 10);
        $user_id = $request->get('user_id', null);
        if (empty($user_id)) {
            return $this->error('参数错误');
        }
        $list = new UsersWallet();
        $list = $list->where('user_id', $user_id)->orderBy('id', 'desc')->paginate($limit);
        return response()->json(['code' => 0, 'data' => $list->items(), 'count' => $list->total()]);
    }

    //钱包锁定状态
    public function walletLock(Request $request)
    {
        $id = $request->get('id', 0);

        $wallet = UsersWallet::find($id);
        if (empty($wallet)) {
            return $this->error('参数错误');
        }
        if ($wallet->status == 1) {
            $wallet->status = 0;
        } else {
            $wallet->status = 1;
        }
        try {
            $wallet->save();
            return $this->success('操作成功');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }

    /*
     * 调节账户
     * */
    public function conf(Request $request)
    {
        $id = $request->get('id', 0);
        if (empty($id)) {
            return $this->error('参数错误');
        }
        $result = UsersWallet::find($id);
        if (empty($result)) {
            return $this->error('无此结果');
        }
        $account = Users::where('id', $result->user_id)->value('phone');
        if (empty($account)) {
            $account = Users::where('id', $result->user_id)->value('email');
        }
        $result['account'] = $account;
        return view('admin.user.conf', ['results' => $result]);
    }

    //调节账号  type  1法币交易余额  2法币交易锁定余额 3币币交易余额 4币币交易锁定余额  5杠杆交易余额 6杠杆交易锁定余额 7币币锁仓账户
    public function postConf(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'way' => 'required|string', //增加 increment；减少 decrement
                'type' => 'required|integer|min:1',
                'conf_value' => 'required|numeric|min:0', //值
                'info' => 'required'
            ], [
                'required' => ':attribute 不能为空',
            ], [
                'info' => '调节备注'
            ]);

            $wallet = UsersWallet::find($request->get('id'));
            $user = Users::getById($wallet->user_id);

            //以上验证通过后 继续验证
            $validator->after(function ($validator) use ($wallet, $user) {
                if (empty($wallet)) {
                    return $validator->errors()->add('wallet', '没有此钱包');
                }

                if (empty($user)) {
                    return $validator->errors()->add('user', '没有此用户');
                }
            });

            //如果验证不通过
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $way = $request->get('way', 'increment');
            $type = $request->get('type', 1);
            $conf_value = $request->get('conf_value', 0);
            $info = $request->get('info', ':');

            $balance_type = ceil($type / 2);
            $is_lock = $type % 2 ? false : true;
            $scene_list = [
                1 => AccountLog::ADMIN_LEGAL_BALANCE,
                2 => AccountLog::ADMIN_LOCK_LEGAL_BALANCE,
                3 => AccountLog::ADMIN_CHANGE_BALANCE,
                4 => AccountLog::ADMIN_LOCK_CHANGE_BALANCE,
                5 => AccountLog::ADMIN_LEVER_BALANCE,
                6 => AccountLog::ADMIN_LOCK_LEVER_BALANCE,
                7 => AccountLog::ADMIN_LOCKED_BALANCE,
            ];

            $way == 'decrement' &&  $conf_value = -$conf_value;

            $result = change_wallet_balance($wallet, $balance_type, $conf_value, $scene_list[$type], $info, $is_lock);
            if ($result !== true) {
                throw new \Exception($result);
            }
            DB::commit();
            return $this->success('操作成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    //删除钱包
    public function delw(Request $request)
    {
        $id = $request->get('id');
        $wallet = UsersWallet::find($id);
        if (empty($wallet)) {
            $this->error("钱包未找到");
        }
        try {
            $wallet->delete();
            return $this->success('删除成功');
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    /*
     * 提币地址信息
     * */
    public function address(Request $request)
    {
        $id = $request->get('id', 0);
        if (empty($id)) {
            return $this->error('参数错误');
        }
        $result = UsersWallet::find($id);
        if (empty($result)) {
            return $this->error('无此结果');
        }


        $list = Address::where('user_id', $result->user_id)->where('currency', $result->currency)->get()->toArray();

        return view('admin.user.address', ['results' => $result, 'list' => $list]);
    }
    /*
     * 修改提币地址信息
     * */
    public function addressEdit(Request $request)
    {
        $user_id = $request->get('user_id', 0);
        $currency = $request->get('currency', 0);
        $total_arr = $request->get('total_arr', '');
        if (empty($user_id) || empty($currency)) {
            return $this->error('参数错误');
        }
        DB::beginTransaction();
        try {
            Address::where('user_id', $user_id)->where('currency', $currency)->delete();
            if (!empty($total_arr)) {
                foreach ($total_arr as $key => $val) {
                    $ads = new Address();
                    $ads->user_id = $user_id;
                    $ads->currency = $currency;
                    $ads->address = $val['address'];
                    $ads->notes = $val['notes'];
                    $ads->save();
                }
            }
            DB::commit();
            return $this->success('修改提币地址成功');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->error($e->getMessage());
        }
    }

    //加入黑名单
    public function blacklist(Request $request)
    {
        $id = $request->get('id', 0);

        $user = Users::find($id);
        if (empty($user)) {
            return $this->error('参数错误');
        }
        if ($user->is_blacklist == 1) {
            $user->is_blacklist = 0;
        } else {
            $user->is_blacklist = 1;
        }
        try {
            $user->save();
            return $this->success('操作成功');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }

    public function candyConf(Request $request, $id)
    {
        $user = Users::find($id);
        return view('admin.user.candy_conf')->with('user', $user);
    }

    public function postCandyConf(Request $request, $id)
    {
        $user = Users::find($id);
        $way = $request->input('way', 0);
        $change = $request->input('change', 0);
        $memo = $request->input('memo', '');
        if (!in_array($way, [1, 2])) {
            return $this->error('调整方式传参错误');
        }
        if ($change <= 0) {
            return $this->error('调整金额必须大于0');
        }
        if ($way == 2) {
            $change = bc_mul($change, -1);
        }
        $result = change_user_candy($user, $change, AccountLog::ADMIN_CANDY_BALANCE, '后台调整' . ($way == 2 ? '减少' : '增加') . '通证 ' . $memo);
        return $result === true ? $this->success('调整成功') : $this->error('调整失败:' . $result);
    }
}
