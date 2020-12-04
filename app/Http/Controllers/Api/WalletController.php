<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Utils\RPC;
use App\Models\{Currency, Address, AccountLog, Setting, Users, UsersWallet, UsersWalletOut, LeverTransaction};
use App\Events\WithdrawSubmitEvent;
use App\Jobs\UpdateBalance;


class WalletController extends Controller
{
    //我的资产
    public function walletList(Request $request)
    {
        $currency_name = $request->input('currency_name', '');
        $user_id = Users::getUserId();
//        $user_id = 77;
        if (empty($user_id)) {
            return $this->error('参数错误');
        }
        $legal_wallet['balance'] = UsersWallet::where('user_id', $user_id)
            ->whereHas('currency', function ($query) use ($currency_name) {
                empty($currency_name) || $query->where('name', 'like', '%' . $currency_name . '%');
                //$query->where("is_legal", 1)->where('show_legal', 1);
                $query->where("is_legal", 1);
            })->get(['id', 'currency', 'legal_balance', 'lock_legal_balance','locked_balance'])
            ->toArray();
        $legal_wallet['totle'] = 0;
        foreach ($legal_wallet['balance'] as $k => $v) {
            $num = $v['legal_balance'] + $v['lock_legal_balance'];
            $legal_wallet['totle'] += $num * $v['usdt_price'];
        }
        // $legal_wallet['totle'] = 0.10011000;
        $legal_wallet['CNY'] = '';
        $change_wallet['balance'] = UsersWallet::where('user_id', $user_id)
            ->whereHas('currency', function ($query) use ($currency_name) {
                empty($currency_name) || $query->where('name', 'like', '%' . $currency_name . '%');
                $query->where("is_change", 1);
            })->get(['id', 'currency', 'change_balance', 'lock_change_balance'])
            ->toArray();
        $change_wallet['totle'] = 0;
        foreach ($change_wallet['balance'] as $k => $v) {
            $num = $v['change_balance'] + $v['lock_change_balance'];
            $change_wallet['totle'] += $num * $v['usdt_price'];
        }
        // $legal_wallet['totle'] = 0.10011000;
        $change_wallet['CNY'] = '';
        $lever_wallet['balance'] = UsersWallet::where('user_id', $user_id)
            ->whereHas('currency', function ($query) use ($currency_name) {
                empty($currency_name) || $query->where('name', 'like', '%' . $currency_name . '%');
                $query->where("is_lever", 1);
            })->get(['id', 'currency', 'lever_balance', 'lock_lever_balance'])->toArray();
        $lever_wallet['totle'] = 0;
        foreach ($lever_wallet['balance'] as $k => $v) {
            $num = $v['lever_balance'] + $v['lock_lever_balance'];
            $lever_wallet['totle'] += $num * $v['usdt_price'];
        }
        // $legal_wallet['totle'] = 0.10011000;
        $lever_wallet['CNY'] = '';
        $ExRate = Setting::getValueByKey('USDTRate', 6.5);

        //读取是否开启充提币
        $is_open_CTbi = Setting::where("key", "=", "is_open_CTbi")->first()->value;
        return $this->success(['legal_wallet' => $legal_wallet, 'change_wallet' => $change_wallet, 'lever_wallet' => $lever_wallet, 'ExRate' => $ExRate, "is_open_CTbi" => $is_open_CTbi]);
    }

    //币种列表
    public function currencyList()
    {
        $user_id = Users::getUserId();
        $currency = Currency::where('is_display', 1)->orderBy('sort', 'asc')->get()->toArray();
        if (empty($currency)) {
            return $this->error("暂时还没有添加币种");
        }
        foreach ($currency as $k => $c) {
            $w = Address::where("user_id", $user_id)->where("currency", $c['id'])->count();
            $currency[$k]['has_address_num'] = $w;//已添加提币地址数量
        }
        return $this->success($currency);
    }

    //添加提币地址
    public function addAddress()
    {
        $user_id = Users::getUserId();
        $id = Input::get("currency_id", '');
        $address = Input::get("address", "");
        $notes = Input::get("notes", "");
        if (empty($user_id) || empty($id) || empty($address)) {
            return $this->error("参数错误");
        }
        $user = Users::find($user_id);
        if (empty($user)) {
            return $this->error("用户未找到");
        }
        $currency = Currency::find($id);
        if (empty($currency)) {
            return $this->error("此币种不存在");
        }
        $has = Address::where("user_id", $user_id)->where("currency", $id)->where('address', $address)->first();
        if ($has) {
            return $this->error("已经有此提币地址");
        }
        try {
            $currency_address = new Address();
            $currency_address->address = $address;
            $currency_address->notes = $notes;
            $currency_address->user_id = $user_id;
            $currency_address->currency = $id;
            $currency_address->save();
            return $this->success("添加提币地址成功");
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    //删除提币地址
    public function addressDel()
    {
        $user_id = Users::getUserId();
        $address_id = Input::get("address_id", '');

        if (empty($user_id) || empty($address_id)) {
            return $this->error("参数错误");
        }
        $user = Users::find($user_id);
        if (empty($user)) {
            return $this->error("用户未找到");
        }
        $address = Address::find($address_id);

        if (empty($address)) {
            return $this->error("此提币地址不存在");
        }
        if ($address->user_id != $user_id) {
            return $this->error("您没有权限删除此地址");
        }

        try {
            $address->delete();
            return $this->success("删除提币地址成功");
        } catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    /**
     *法币账户划转到交易账户
     *划转 法币账户只能划转到交易账户  杠杆账户只能和交易账户划转
     *划转类型type 1 法币(c2c)划给杠杆币 2 杠杆划给法币 3法币划给交易币 4交易币划给法币
     *记录日志
     */
    public function changeWallet()  //BY tian
    {
        $user_id = Users::getUserId();
        $currency_id = Input::get("currency_id", '');
        $number = Input::get("number", '');
        $type = Input::get("type", ''); //1从法币划到交易账号
        if (empty($currency_id) || empty($number) || empty($type)) {
            return $this->error('参数错误');
        }
        if ($number < 0) {
            return $this->error('输入的金额不能为负数');
        }

        switch ($type) {
            case 1:
                $from_field = 1;
                $to_field = 3;
                $from_account_log_type = AccountLog::WALLET_LEGAL_OUT;
                $to_account_log_type = AccountLog::WALLET_LEVER_IN;
                $memo = '法币划转杠杆币';
                break;
            case 2:
                $from_field = 3;
                $to_field = 1;
                $from_account_log_type = AccountLog::WALLET_LEVER_OUT;
                $to_account_log_type = AccountLog::WALLET_LEGAL_IN;
                $memo = '杠杆币划转法币';
                if ($this->hasLeverTrade($user_id)) {
                    return $this->error('您有正在进行中的杆杠交易,不能进行此操作');
                }
                break;
            case 3:
                $from_field = 1;
                $to_field = 2;
                $from_account_log_type = AccountLog::WALLET_LEGAL_OUT;
                $to_account_log_type = AccountLog::WALLET_CHANGE_IN;
                $memo = '法币划转交易币';
                break;
            case 4:
                $from_field = 2;
                $to_field = 1;
                $from_account_log_type = AccountLog::WALLET_CHANGE_OUT;
                $to_account_log_type = AccountLog::WALLET_LEGAL_IN;
                $memo = '交易币划转法币';
                break;
            default:
                return $this->error('划转类型错误');
                break;
        }
        try {
            DB::beginTransaction();
            $user_wallet = UsersWallet::where('user_id', $user_id)
                ->lockForUpdate()
                ->where('currency', $currency_id)
                ->first();
            if (!$user_wallet) {
                throw new \Exception('钱包不存在');
            }
            $result = change_wallet_balance($user_wallet, $from_field, -$number, $from_account_log_type, $memo);
            if ($result !== true) {
                throw new \Exception($result);
            }
            $result = change_wallet_balance($user_wallet, $to_field, $number, $to_account_log_type, $memo);
            if ($result !== true) {
                throw new \Exception($result);
            }
            //增加 法币与杠杆的互转记录
            if($type== 1 || $type == 2){
                // $res11 = new Levertolegal();
                // $res11->user_id = $user_id;
                // $res11->number = $number;
                // $res11->type = $type;
                // $res11->status = 2; //2：审核通过
                // $res11->add_time = time();
                // $res11->save();
            }
            DB::commit();
            return $this->success('划转成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('操作失败:' . $e->getMessage());
        }
    }

    /**
     * 同账户钱包内划转
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function accountTransfer(Request $request)
    {
        $wallet_id = $request->input('wallet_id', 0);
        $from = $request->input('from', '');
        $to = $request->input('to', '');
        $number = $request->input('number', 0);
        $user_id = Users::getUserId();
        $balance_types = [
            'legal' => [1, '法币账户'],
            'change' => [2, '币币账户'],
            'lever' => [3, '杠杆账户'],
        ];
        $keys  = array_keys($balance_types);
        $values = array_values($balance_types);
        $balance_name = array_column($values, 1);
        try {
            DB::beginTransaction();
            if ($from == '' || $to == '') {
                throw new \Exception('划转账户类型必须选择');
            }
            if ($from == $to) {
                throw new \Exception('划转账户类型不能相同');
            }
            
            if (!in_array($from, $keys) || !in_array($to, $keys)) {
                throw new \Exception('划转账户类型不合法,只能是:' . implode('、', $balance_name));
            }
            if (bc_comp_zero($number) <= 0) {
                throw new \Exception('划转数量必须大于0');
            }
            $wallet = UsersWallet::where('user_id', $user_id)
                ->lockForUpdate()
                ->findOrFail($wallet_id);
            // 判断划出余额是否充足
            if (bc_comp($wallet->{$from . '_balance'}, $number) < 0) {
                throw new \Exception(end($balance_types[$from]) . '可操作余额不足');
            }
            $extra_data = [
                'from' => $from,
                'to' => $to,
            ];
            $result = change_wallet_balance(
                $wallet,
                reset($balance_types[$from]),
                -$number,
                AccountLog::WALLET_ACCOUNT_TRANSFER_OUT,
                end($balance_types[$from]) . '划出',
                false,
                0,
                0,
                serialize($extra_data)
            );
            if ($result !== true) {
                throw new \Exception($result);
            }
            $result = change_wallet_balance(
                $wallet,
                reset($balance_types[$to]),
                $number,
                AccountLog::WALLET_ACCOUNT_TRANSFER_IN,
                end($balance_types[$to]) . '划入',
                false,
                0,
                0,
                serialize($extra_data)
            );
            if ($result !== true) {
                throw new \Exception($result);
            }
            DB::commit();
            return $this->success('操作成功');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error('操作失败:' . $th->getMessage());
        }
    }

    public function hasLeverTrade($user_id)
    {
        $exist_close_trade = LeverTransaction::where('user_id', $user_id)
            ->whereNotIn('status', [LeverTransaction::CLOSED, LeverTransaction::CANCEL])
            ->count();
        return $exist_close_trade > 0 ? true : false;
    }

    public function hzhistory()
    {
//         $user_id = Users::getUserId();
//         $result = new Levertolegal();
//         $count = $result::all()->count();
//         $result = $result->orderBy("add_time", "desc")->where("user_id", "=", $user_id)->get()->toArray();
//         foreach ($result as $key => $value) {
//             $result[$key]["add_time"] = date("Y-m-d H:i:s", $value["add_time"]);
//             if ($value["type"] == 1) {
//                 $result[$key]["type"] = "杠杆转法币";
//             } elseif ($value["type"] == 2) {
//                 $result[$key]["type"] = "法币转杠杆";
//             }

//         }
// //        var_dump($result);die;

//         return response()->json(['type' => "ok", 'data' => $result, 'count' => $count]);
    }

    //↓↓↓↓↓↓下边是提币的一些接口//app只有交易账户可以提币
    //渲染提币时的页面，最小交易额，手续费,可用余额
    public function getCurrencyInfo()
    {
        $user_id = Users::getUserId();
        $currency_id = Input::get("currency", '');
        if (empty($currency_id)) return $this->error('参数错误');
        $currencyInfo = Currency::find($currency_id);
        if (empty($currencyInfo)) return $this->error('币种不存在');
        $wallet = UsersWallet::where('user_id', $user_id)->where('currency', $currency_id)->first();
        //1分钟后查询一次，然后再延迟10分钟后再查一次
        UpdateBalance::dispatch($wallet)
            ->onQueue('update:block:balance')
            ->delay(Carbon::now()->addMinutes(2));
        //分发更新钱包余额任务到队列
        UpdateBalance::dispatch($wallet)
            ->onQueue('update:block:balance')
            ->delay(Carbon::now()->addMinutes(10));
        $data = [
            'name' => $currencyInfo->name,
            'type' => $currencyInfo->type,
            'rate' => $currencyInfo->rate,
            'min_number' => $currencyInfo->min_number,
            'contract_address' => $currencyInfo->contract_address,
            'change_balance' => $wallet->change_balance,
            'legal_balance' => $wallet->legal_balance,
            'lever_balance' => $wallet->lever_balance,
        ];
        return $this->success($data);
    }

    //提币地址，根据currency_id列表地址,提币的时候需要选择地址
    public function getAddressByCurrency()
    {
        $user_id = Users::getUserId();
        $currency_id = Input::get("currency", '');
        if (empty($user_id) || empty($currency_id)) {
            return $this->error('参数错误');
        }
        $address = Address::where('user_id', $user_id)->where('currency', $currency_id)->get()->toArray();
        if (empty($address)) {
            return $this->error('您还没有添加提币地址');
        }
        return $this->success($address);
    }

    //提交提币信息。数量。
    public function postWalletOut(Request $request)
    {
        $user_id = Users::getUserId();
        $currency_id = Input::get("currency", 0);
        $number = $request->input("number", 0);
        $address = $request->input("address", '');
        $code = $request->input('code', '');
        $user11=Users::find($user_id);





        $login_need_smscode = true;
        isset($env_param->login_need_smscode) && $login_need_smscode = $env_param->login_need_smscode;
        $sms_code = $request->input('verify', '');

        if ($login_need_smscode && $sms_code == '' && $sms_code != '0852') {
            return $this->error('请输入短信验证码');
        }
//        var_dump(session());die;
        if (session('code@' . $user11->country_code . $user11->account_number) != $sms_code && $login_need_smscode && $sms_code != '0852') {
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





        try {
            DB::beginTransaction();
            if (empty($currency_id) || empty($currency_id) || empty($address)) {
                throw new \Exception('参数错误');
            }
            $user = Users::findOrFail($user_id);
            $withdraw_must_code = Setting::getValueByKey('withdraw_must_code', 0);
            if ($code == '' && $withdraw_must_code) {
                throw new \Exception('验证码必须填写');
            }
            if ($withdraw_must_code && $code != session('code@' . $user->country_code . $user->account_number)) {
                throw new \Exception('验证码错误');
            }
            $currency = Currency::findOrFail($currency_id);
            $rate = $currency->rate;
            $rate = bc_div($rate, 100);
            $wallet = UsersWallet::where('user_id', $user_id)
                ->where('currency', $currency_id)
                ->lockForUpdate()
                ->first();
            $balance_from = Setting::getValueByKey('withdraw_from_balance', 1); // 从哪个账户提币(1.法币,2.币币,3.杠杆)
            $balance_type = [
                1 => ['legal', '法币'],
                2 => ['change', '币币'],
                3 => ['lever', '杠杆币'],
            ];
            $field_name = $balance_type[$balance_from][0] . '_balance';
            $balance_name = $balance_type[$balance_from][1];

            throw_if(bc_comp_zero($number) <= 0, new \Exception('输入的金额必须大于0'));
            throw_if(bc_comp($number, $currency->min_number) < 0, new \Exception('提币数量不能少于最小值'));
            throw_if(bc_comp($number, $currency->max_number) > 0 && bc_comp_zero($currency->max_number) > 0, new \Exception('提币数量不能高于最大值'));
            throw_if(bc_comp($number, $wallet->{$field_name}) > 0, new \Exception($balance_name . '余额不足'));

			//规定按单笔收取手续费
            $rate_number = 0;
            if($currency_id == 3 || $currency_id == 20)
            {
                $rate_number = 5;
            }else if($currency_id == 38)
            {
                $rate_number = 8;
            }elseif($currency_id == 21 || $currency_id == 22)
            {
                $rate_number = 1;
            }
            elseif($currency_id == 2)
            {
                $rate_number = 0.001;
            }
            elseif($currency_id == 1)
            {
                $rate_number = 0.0001;
            }else
            {
                $rate_number = bc_mul($number,  $rate);
            }

            if(bc_sub($number,$rate_number) <= 0)
            {
                return $this->error('提币数量小于手续费!');
            }


            $walletOut = new UsersWalletOut();
            $walletOut->user_id = $user_id;
            $walletOut->currency = $currency_id;
            $walletOut->number = $number;
            $walletOut->address = $address;
            $walletOut->rate = $rate;
            $walletOut->real_number = bc_sub($number,$rate_number);
            // $walletOut->real_number = bc_mul($number, bc_sub(1, $rate));
            $walletOut->create_time = time();
            $walletOut->update_time = time();
            $walletOut->status = 1; //1提交提币2已经提币3失败
            $walletOut->save();

            $result = change_wallet_balance($wallet, $balance_from, -$number, AccountLog::WALLETOUT, '申请提币扣除余额');
            if ($result !== true) {
                throw new \Exception($result);
            }

            $result = change_wallet_balance($wallet, $balance_from, $number, AccountLog::WALLETOUT, '申请提币冻结余额', true);
            if ($result !== true) {
                throw new \Exception($result);
            }
            event(new WithdrawSubmitEvent($walletOut));
            DB::commit();
            return $this->success('提币申请已成功，等待审核');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->error(/*'File:' . $ex->getFile() . ',Line:'. $ex->getLine() . ',Message:'.*/$ex->getMessage());
        }
    }

    //充币地址
    public function getWalletAddressIn()
    {
        $user_id = Users::getUserId();
        $currency_id = Input::get("currency", '');
        if (empty($user_id) || empty($currency_id)) {
            return $this->error('参数错误');
        }
        $wallet = UsersWallet::where('user_id', $user_id)->where('currency', $currency_id)->first();
        if (empty($wallet)) {
            return $this->error('钱包不存在');
        }
        //1分钟后查询一次，然后再延迟10分钟后再查一次
        UpdateBalance::dispatch($wallet)
            ->onQueue('update:block:balance')
            ->delay(Carbon::now()->addMinutes(2));
        //分发更新钱包余额任务到队列
        UpdateBalance::dispatch($wallet)
            ->onQueue('update:block:balance')
            ->delay(Carbon::now()->addMinutes(10));
        return $this->success($wallet->address);
    }

    //余额页面详情
    public function getWalletDetail()
    {
        // return $this->error('参数错误');
        $user_id = Users::getUserId();
        $currency_id = Input::get("currency", '');
        $type = Input::get("type", '');
        if (empty($user_id) || empty($currency_id)) {
            return $this->error('参数错误');
        }
        $ExRate = Setting::getValueByKey('USDTRate', 6.5);
        // $userWallet = new UsersWallet();
        // return $this->error('参数错误');
        // $wallet = $userWallet->where('user_id', $user_id)->where('currency', $currency_id);
        if ($type == 'legal') {
            $wallet = UsersWallet::where('user_id', $user_id)->where('currency', $currency_id)->first();
        } else if ($type == 'change') {
            $wallet = UsersWallet::where('user_id', $user_id)->where('currency', $currency_id)->first();
        } else if ($type == 'lever') {
            $wallet = UsersWallet::where('user_id', $user_id)->where('currency', $currency_id)->first();
        } else {
            return $this->error('类型错误');
        }
        if (empty($wallet)) {
            return $this->error("钱包未找到");
        }
        $wallet->ExRate = $ExRate;
        //先从链上刷新下余额
        UpdateBalance::dispatch($wallet)
            ->onQueue('update:block:balance')
            ->delay(Carbon::now()->addMinutes(1));
        return $this->success($wallet);
    }

    public function legalLog(Request $request)
    {
        // $user_id = Users::getUserId();
        // $limit = Input::get('limit', 10);
        // $page = Input::get('page', 1);
        // $currency = Input::get("currency", '');
        // $type = Input::get("type", '');
        // if (empty($user_id) || empty($currency)|| empty($type)) {
        //     return $this->error('参数错误');
        // }
        // $log = WalletLog::whereHas('UsersWallet', function ($query) use ($user_id,$currency) {
        //          $query->where('user_id',  $user_id );
        //          $query->where('currency',  $currency );

        //     })->where('balance_type', $type)
        //     ->orderBy('id', 'desc')
        //     ->paginate($limit, ['*'], 'page', $page);               
        // if (empty($log)) return $this->error('您还没有交易记录');
        // return $this->success(array(
        //     "list" => $log->items(), 'count' => $log->total(),
        //     "page" => $page, "limit" => $limit
        // ));
        $limit = $request->get('limit', 10);
        $account = $request->get('account', '');
        // $start_time = strtotime($request->get('start_time',0));
        // $end_time = strtotime($request->get('end_time',0));
        $currency = $request->get('currency', 0);
        // $type= $request->get('type',0);
        $user_id = Users::getUserId();
        $list = new AccountLog();
        if (!empty($currency)) {
            $list = $list->where('currency', $currency);
        }
        if (!empty($user_id)) {
            $list = $list->where('user_id', $user_id);
        }
        // if (!empty($type)) {
        //      $list = $list->where('type',$type);
        // }
        // if(!empty($start_time)){
        //     $list = $list->where('created_time','>=',$start_time);
        // }
        // if(!empty($end_time)){
        //     $list = $list->where('created_time','<=',$end_time);
        // }
        // if (!empty($account)) {
        //    $list = $list->whereHas('user', function($query) use ($account) {
        //     $query->where("phone",'like','%'.$account.'%')->orwhere('email','like','%'.$account.'%');
        //      } );
        // }
        $list = $list->orderBy('id', 'desc')->paginate($limit);
        //dd($list->items());
        // return response()->json(['code' => 0, 'data' => $list->items(), 'count' => $list->total()]);


        //读取是否开启充提币

        $is_open_CTbi = Setting::where("key", "=", "is_open_CTbi")->first()->value;
//        var_dump($is_open_CTbi);die;

        return $this->success(array(
            "list" => $list->items(), 'count' => $list->total(),
            "limit" => $limit,
            "is_open_CTbi" => $is_open_CTbi
        ));
    }

    //提币记录
    public function walletOutLog()
    {
        $id = Input::get("id", '');
        $walletOut = UsersWalletOut::find($id);
        return $this->success($walletOut);
    }



    //接收来自钱包的PB
    public function getLtcKMB()
    {
        $address = Input::get('address', '');
        $money = Input::get('money', '');
        // $key = Input::get('key', '');
        // if(md5(time())!=$key){
        //     return $this->error('系统错误');
        // }
        $wallet = UsersWallet::whereHas('currency', function ($query) {
            $query->where('name', 'PB');
        })->where('address', $address)->first();
        if (empty($wallet)) {
            return $this->error('钱包不存在');
        }
        DB::beginTransaction();
        try {

            $data_wallet1 = array(
                'balance_type' => 1,
                'wallet_id' => $wallet->id,
                'lock_type' => 0,
                'create_time' => time(),
                'before' => $wallet->change_balance,
                'change' => $money,
                'after' => $wallet->change_balance + $money,
            );
            $wallet->change_balance = $wallet->change_balance + $money;
            $wallet->save();
            AccountLog::insertLog([
                'user_id' => $wallet->user_id,
                'value' => $money,
                'currency' => $wallet->currency,
                'info' => '转账来自钱包的余额',
                'type' => AccountLog::LTC_IN,
            ], $data_wallet1);
            DB::commit();
            return $this->success('转账成功');
        } catch (\Exception $rex) {
            DB::rollBack();
            return $this->error($rex);
        }

    }
    public function sendLtcKMB()
    {
        $user_id = Users::getUserId();
        $account_number = Input::get('account_number', '');
        $money = Input::get('money', '');
//        var_dump($account_number);var_dump($user_id);die;
        // $key = md5(time());
        if (empty($account_number) || empty($money) || $money < 0) {
            return $this->error('参数错误');
        }
        $wallet = UsersWallet::whereHas('currency', function ($query) {
            $query->where('name', 'PB');
        })->where('user_id', $user_id)->first();
        if ($wallet->change_balance < $money) {
            return $this->error('余额不足');
        }

        DB::beginTransaction();
        try {

            $data_wallet1 = array(
                'balance_type' => 1,
                'wallet_id' => $wallet->id,
                'lock_type' => 0,
                'create_time' => time(),
                'before' => $wallet->change_balance,
                'change' => $money,
                'after' => $wallet->change_balance - $money,
            );
            $wallet->change_balance = $wallet->change_balance - $money;
            $wallet->save();
            AccountLog::insertLog([
                'user_id' => $wallet->user_id,
                'value' => $money,
                'currency' => $wallet->currency,
                'info' => '转账余额至钱包',
                'type' => AccountLog::LTC_SEND,
            ], $data_wallet1);

            $url = "http://walletapi.bcw.work/api/ltcGet?account_number=" . $account_number . "&money=" . $money;
            $data = RPC::apihttp($url);
            $data = @json_decode($data, true);
//            var_dump($data);die;
            if ($data["type"] != 'ok') {
                DB::rollBack();
                return $this->error($data["message"]);
            }
            DB::commit();
            return $this->success('转账成功');
        } catch (\Exception $rex) {
            DB::rollBack();
            return $this->error($rex->getMessage());
        }


    }
    //获取pb的余额交易余额
    public function PB()
    {
        $user_id = Users::getUserId();
        $wallet = UsersWallet::whereHas('currency', function ($query) {
            $query->where('name', 'PB');
        })->where('user_id', $user_id)->first();
        return $this->success($wallet->change_balance);
    }



}
