<?php

namespace App\Http\Controllers\Api;


use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Models\{AppApi, UserChat, Users, UserReal, Token, AccountLog, UsersWallet, UsersWalletcopy, Currency, InviteBg, Setting, UserCashInfo, ExchangeShiftTo,Optional,Redis};
use App\DAO\RPC;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;


class RedisController extends Controller
{


    public function redis_test(Request $request)
    {

        $name = $request->get('name');


        $redis = app('redis.connection');
        $redis->set('name', $name); // 存储 key 为 name， 值为 tianliang 的记录；
        $redis->set('create_time', date("Y-m-d H:i:s",time()));


        $new=new Redis();
        $new->name=$redis->get('name');// 获取 key 为 name 的记录值
        $new->create_time=$redis->get('create_time');
        $new->save();





    }


    public function zixuan_list(Request $request){
        $user_id = Users::getUserId();
        $list=Optional::where("user_id",$user_id)->where("status",1)->get()->toArray();
        return $this->success($list);
    }

    public function addzixuan(Request $request)
    {
        $user_id = Users::getUserId();
        if(empty($user_id)){
            return $this->error("请登录");
        }
        $currency_id = $request->get('currency_id');
        $legal_id = $request->get('legal_id');
        if (empty($legal_id) || empty($currency_id)) {
            return $this->error('参数错误');
        }
        $optional=new Optional();
        $is_hava=$optional->where("user_id",$user_id)->where("legal_id",$legal_id)->where("currency_id",$currency_id)->first();
//        var_dump($currency_id);var_dump($legal_id);var_dump($is_hava->toArray());die;
        if(!empty($is_hava->legal_id)){//0未选   1已选
            if($is_hava->status==0){
                $is_hava->status=1;
            }else{
                $is_hava->status=0;
            }
            $is_hava->save();
            return $this->success('成功');
        }else{//还未有数据
            $optional->status=1;
            $optional->user_id=$user_id;
            $optional->currency_id=$currency_id;
            $optional->legal_id=$legal_id;
            $optional->save();
            return $this->success('成功');
        }

    }

    //添加/修改收款方式
    public function saveCashInfo(Request $request)
    {
        $bank_name = $request->get('bank_name', ''); //开户行
        $bank_account = $request->get('bank_account', ''); //银行账号
        $real_name = $request->get('real_name', ''); //真实姓名,渲染出来
        $alipay_account = $request->get('alipay_account', ''); //支付宝账号
        $alipay_collect = $request->get('alipay_collect', ''); //支付宝收款码
        $wechat_nickname = $request->get('wechat_nickname', ''); //微信昵称
        $wechat_account = $request->get('wechat_account', ''); //微信账号
        $wechat_collect = $request->get('wechat_collect', ''); //微信收款码
        $user_id = Users::getUserId();
        if (empty($real_name)) {
            return $this->error('真实姓名必须填写');
        }
        if (
            (empty($bank_name) || empty($bank_account))
            && (empty($wechat_nickname) || empty($wechat_account) || empty($wechat_collect))
            && (empty($alipay_account) || empty($alipay_collect))
            ) {
            return $this->error('收款信息至少选填一项');
        }
        if (empty($user_id)) {
            return $this->error('参数错误');
        }
        $cash_info = UserCashInfo::where('user_id', $user_id)->first();
        if (empty($cash_info)) {
            $cash_info = new UserCashInfo();
            $cash_info->user_id = $user_id;
            $cash_info->create_time = time();
        }
        if (!empty($bank_name)) {
            $cash_info->bank_name = $bank_name;
        }
        if (!empty($bank_account)) {
            $cash_info->bank_account = $bank_account;
        }
        $cash_info->real_name = $real_name;
        if (!empty($alipay_account)) {
            $cash_info->alipay_account = $alipay_account;

        }
        if (!empty($wechat_account)) {
            $cash_info->wechat_account = $wechat_account;

        }
        if (!empty($wechat_nickname)) {
            $cash_info->wechat_nickname = $wechat_nickname;

        }
        if (!empty($alipay_collect)) {
            $cash_info->alipay_collect = $alipay_collect;

        }
        if (!empty($wechat_collect)) {
            $cash_info->wechat_collect = $wechat_collect;
        }
        try {
            $cash_info->save();
            return $this->success('保存成功');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }

    public function checkPayPassword()
    {
        $password = Input::get('password', '');
        $user = Users::getById(Users::getUserId());
        if ($user->pay_password != Users::MakePassword($password)) {
            return $this->error('密码错误');
        } else {
            return $this->success('操作成功');
        }
    }

    public function candy_tousdt()
    {
        $candy_tousdt = Setting::getValueByKey('candy_tousdt', 100);
        $candy_tousdt = bc_div($candy_tousdt, 100);
        $user_id = Users::getUserId();
        $user = Users::find($user_id);
        $candy_number = Input::get('candy_number');
        if (empty($candy_number) || $candy_number <= 0) {
            return $this->error('参数错误!');
        }
        if ($candy_number > $user->candy_number) {
            return $this->error('兑换数量大于剩余数量!');
        }
        DB::beginTransaction();
        try {
            $change_result = change_user_candy($user, -$candy_number, AccountLog::CANDY_TOUSDT_CANDY, "通证兑换USDT");
            if ($change_result !== true) {
                throw new \Exception($change_result);
            }
            $aaaa = UsersWalletcopy::leftjoin("currency", "currency.id", "users_wallet.currency")
                ->where("currency.name", "USDT")
                ->where("users_wallet.user_id", $user_id)
                ->select("users_wallet.id", "users_wallet.lever_balance", "users_wallet.user_id", "currency.id as currency_id")
                ->first();
            $user_walllet = UsersWalletcopy::where("user_id", $aaaa->user_id)
                ->where("currency", $aaaa->currency_id)
                ->first();
            /*
            $user_walllet->lever_balance=bc_add($user_walllet->lever_balance,$candy_number*$candy_tousdt,8);
            var_dump($user_walllet->toArray());
            die;
            $user_walllet->save();
            */
            $change = bc_mul($candy_number, $candy_tousdt, 4);
            //增加杠杆币日志记录
            $result = change_wallet_balance(
                $user_walllet,
                3,
                $change,
                AccountLog::CANDY_LEVER_BALANCE,
                '通证兑换,杠杆币增加' . $change,
                false,
                $user->id,
                0
            );
            if ($result !== true) {
                throw new \Exception('通证兑换杠杆币增加失败:' . $result);
            }
            DB::commit();
            return $this->success('通证兑换成功!');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->error($ex->getMessage());
        }
    }



     //获取本人收款方式信息
    public function cashInfo()
    {
        $user_id = Users::getUserId();
        if (empty($user_id)) {
            return $this->error('参数错误');
        }
        $result = UserCashInfo::where('user_id', $user_id)->firstOrFail();
        return $this->success($result);
    }
    //设置法币交易账号密码
    public function setAccount()
    {
        $account = Input::get('account', '');
        $password = Input::get('password', '');
        $repassword = Input::get('repassword', '');
        if (empty($account) || empty($password) || empty($repassword)) {
            return $this->error('必填项信息不完整');
        }
        if ($password != $repassword) {
            return $this->error('两次输入密码不一致');
        }
        $user_id = Users::getUserId();
        $user = Users::find($user_id);
        if (empty($user)) {
            return $this->error('此用户不存在');
        }
        if ($user->account_number) {
            return $this->error('此交易账号已经设置');
        }
        $res = Users::where('account_number', $account)->first();
        if ($res) {
            return $this->error('此账号已经存在');
        }
        try {
            $user->account_number = $account;
            $user->pay_password = Users::MakePassword($password, $user->type);
            $user->save();
            return $this->success('交易账号设置成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    //安全中心-->电话邮箱绑定信息
    public function safeCenter()
    {
        $user_id = Users::getUserId();
        $user = Users::find($user_id);
        $safeInfo = array(
            'mobile' => $user->phone,//如果为空，未绑定
            'email' => $user->email,
            'gesture_password' => $user->gesture_password,
            //手势密码如果存在就是个蓝色的框，默认登录的时候就是手势密码登录
            //再次点击蓝色的框就是取消手势密码，取消就不用手势密码登录，删除字段中的值
            //如果不存在，是个灰色的框。点击之后是重新设置添加手势密码
        );
        return $this->success($safeInfo);
    }
    //安全中心-->绑定电话
    public function setMobile()
    {
        $user_id = Users::getUserId();
        $mobile = Input::get('mobile', '');
        $code = Input::get('code', '');
        if (empty($user_id) || empty($mobile) || empty($code)) {
            return $this->error('参数错误');
        }
        if ($code != session('code')) {
            return $this->error('验证码错误');
        }
        try {
            $user = Users::find($user_id);
            $user->phone = $mobile;
            $user->save();
            return $this->success('手机绑定成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
    //安全中心-->绑定邮箱
    public function setEmail()
    {
        $user_id = Users::getUserId();
        $email = Input::get('email', '');
        $code = Input::get('code', '');
        if (empty($user_id) || empty($email) || empty($code)) {
            return $this->error('参数错误');
        }
        if ($code != session('code')) {
            return $this->error('验证码错误');
        }
        try {
            $user = Users::find($user_id);
            $user->email = $email;
            $user->save();
            return $this->success('邮箱绑定成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    //安全中心-->手势密码-->添加手势密码
    public function gesturePassAdd()
    {
        $password = Input::get('password', '');//获取的是一个数组[1,2,3]
        $re_password = Input::get('re_password', '');
        if (mb_strlen($password) < 6) {
            return $this->error('手势密码至少连接6个点');
        }
        if ($password != $re_password) {
            return $this->error('两次手势密码不相同');
        }
        $user_id = Users::getUserId();
        $user = Users::find($user_id);
        $user->gesture_password = $password;
        try {
            $user->save();
            return $this->success('手势密码添加成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
    //安全中心-->手势密码-->取消手势密码
    public function gesturePassDel()
    {
        $user_id = Users::getUserId();
        $user = Users::find($user_id);
        $user->gesture_password = "";
        try {
            $user->save();
            return $this->success('取消手势密码成功');//按钮变成灰色的
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
    
    //安全中心-->修改交易密码
    public function updatePayPassword()
    {
        $user_id = Users::getUserId();
        $user = Users::find($user_id);
        // $oldpassword = Input::get('oldpassword', '');
        $password = Input::get('password', '');
        $re_password = Input::get('re_password', '');
        $code = Input::get('code', '');
        $country_code = $user->country_code;
        if ($code == '' ) {
            return $this->error('验证码必须填写');
        }
        if (mb_strlen($password) < 6 || mb_strlen($password) > 16) {
            return $this->error('密码只能在6-16位之间');
        }
        if ($password != $re_password) {
            return $this->error('两次密码不一致');
        }

        if ($code != session('code@' . $country_code . $user->account_number) ) {
            //万能验证码
            $universalCode=Setting::getValueByKey('change_password_universalCode','');
            if($universalCode){
                if($code != $universalCode){
                    return $this->error('验证码错误');
                }

            }else{
                return $this->error('验证码错误');
            }
           
        }

        // if (Users::MakePassword($oldpassword) != $user->pay_password) {
        //        return $this->error('原密码错误');
        // }

        $user->pay_password = Users::MakePassword($password);
        try {
            $user->save();
            return $this->success('交易密码设置成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    //邀请返佣榜单  前20名
    public function inviteList()
    {
        $time = Input::get('time', '');//邀请返佣时间段
        if ($time) {
            $time = strtotime($time);
        } else {
            $time = 0;
        }


        $list = AccountLog::has('user')
            ->select(DB::raw('sum(value) as total, user_id'))
            ->where('type', AccountLog::INVITATION_TO_RETURN)
            ->where('created_time', '>=', $time)
            ->groupBy('user_id')
            ->orderBy('total', 'desc')

            ->limit(20)
            ->get()
            ->toArray();

        if (empty($list)) {
            return $this->error("暂时还没有邀请排行榜，快去邀请吧");
        }


        foreach ($list as $key => $val) {

            $user = Users::find($val['user_id']);


            $list[$key]['account'] = $user->account;

        }

        return $this->success($list);


    }
    

      //邀请 
    public function invite()
    {

        $user_id = Users::getUserId();
        $user = Users::where("id", $user_id)->first();

        if (empty($user)) {
            return $this->error("会员未找到");
        }

       
        //邀请排行榜 前3
        $list = AccountLog::has('user')
            ->select(DB::raw('sum(value) as total, user_id'))
            ->where('type', AccountLog::INVITATION_TO_RETURN)

            ->groupBy('user_id')
            ->orderBy('total', 'desc')

            ->limit(3)
            ->get()
            ->toArray();
        if (empty($list)) {
            $list = [];
        } else {

            foreach ($list as $key => $val) {

                $users = Users::find($val['user_id']);

                $list[$key]['account'] = $users->account;

            }


        }

        //邀请广告图片及链接 
        $ad = [];
        $ad['image'] = "/upload/invite.png";

        $data = [];
        $data['extension_code'] = $user['extension_code'];
        $data['ad'] = $ad;
        $data['inviteList'] = $list;


        //获取用户邀请人数
        //邀请返佣金数量
        $num = Users::where('parent_id', $user_id)->count();

        if ($num > 0) {
            $data['invite_num'] = $num;
            $total = AccountLog::where('user_id', $user_id)->where('type', AccountLog::INVITATION_TO_RETURN)->sum('value');
            $data['invite_return_total'] = $total;
        } else {
            $data['invite_num'] = 0;
            $data['invite_return_total'] = 0;
        }

        return $this->success($data);

    }

    
    //我的邀请记录  0启用  1禁用 2全部 
    public function myInviteList()
    {

        $status = Input::get('status', 2);//邀请会员状态
        $user_id = Users::getUserId();
        $user = Users::where("id", $user_id)->first();

        if (empty($user)) {
            return $this->error("会员未找到");
        }

        $list = Users::where('parent_id', $user_id);
        if ($status != 2) {
            $list = $list->where('status', $status);
        }
        $list = $list->orderBy('id', 'desc')->get()->toArray();

        return $this->success($list);

    }

    //我的返佣记录
    public function myAccountReturn()
    {

        $user_id = Users::getUserId();
        $user = Users::where("id", $user_id)->first();

        if (empty($user)) {
            return $this->error("会员未找到");
        }

        $time = Input::get('time', '');//邀请返佣时间段
        if ($time) {
            $time = strtotime($time);
        } else {
            $time = 0;
        }


        $list = AccountLog::where('user_id', $user_id)

            ->where('type', AccountLog::INVITATION_TO_RETURN)
            ->where('created_time', '>=', $time)

            ->orderBy('id', 'desc')
            ->get()
            ->toArray();


        return $this->success($list);


    }

    //钱包地址
    public function walletaddress()
    {
//        $user_id = Users::getUserId();
        $user_id = Input::get('user_id');
        $wallet_address = Input::get('wallet_address');

        $usermyself = Users::where("id", $user_id)->first()->toArray();
        $user = Users::where("wallet_address", $wallet_address)->where("id", '!=', $user_id)->first();
        if ($usermyself['wallet_address']) {
            return $this->error("你已绑定，不可更改!");
        } elseif (!empty($user)) {
            return $this->error("该地址已被绑定,请重新输入");
        } else {
            $pdo = new Users();
            $pdo->where("id", "=", $user_id)->update(['wallet_address' => $wallet_address]);
            return $this->success('绑定成功!');
        }
    }



    //我的  
    public function info()
    {
        $user_id = Users::getUserId();  
        //$user = Users::where("id",$user_id)->first(['id','phone','email','head_portrait','status']);
        
        try {
            $user = Users::where("id", $user_id)->first();
            if (empty($user)) {
                throw new \Exception("会员未找到");
            }
            
            //用户认证状况
            $res = UserReal::where('user_id', $user_id)->first();
            if (empty($res)) {
                $user['review_status'] = 0;
                $user['name'] = '';
            } else {
                $user['review_status'] = $res['review_status'];
                $user['name'] = $res['name'];
            }
    
            return $this->success($user);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

     //身份认证
    public function realName()
    {

        $user_id = Users::getUserId();
        $name = Input::get("name", "");//真实姓名
        $card_id = Input::get("card_id", "");//身份证号
        $front_pic = Input::get("front_pic", "");//正面照片
        $reverse_pic = Input::get("reverse_pic", "");//反面照片
        $hand_pic = Input::get("hand_pic", "");//手持身份证照片

        if (empty($name) || empty($card_id) || empty($front_pic) || empty($reverse_pic) || empty($hand_pic)) {
            return $this->error("请提交完整的信息");
        }  

        //校验  身份证号码合法性
        /*
        $idcheck = new IdCardIdentity();
        $res = $idcheck->check_id($card_id);
        if (!$res) {
            return $this->error("请输入合法的身份证号码");
        }
        */
        $userreal_number = UserReal::where('card_id', $card_id)->count();
        if($userreal_number > 0) {
            return $this->error("该身份证号已使用!");
        }
        $user = Users::find($user_id);
        if (empty($user)) {
            return $this->error("会员未找到");
        }
        $userreal = UserReal::where('user_id', $user_id)->first();
        if (!empty($userreal)) {
            return $this->error("您已经申请过了");
        }

        try {
            $userreal = new UserReal();
            $userreal->user_id = $user_id;
            $userreal->name = $name;
            $userreal->card_id = $card_id;
            $userreal->create_time = time();
            $userreal->front_pic = $front_pic;
            $userreal->reverse_pic = $reverse_pic;
            $userreal->hand_pic = $hand_pic;
            $userreal->save();
            return $this->success('提交成功，等待审核');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }


    //个人中心  身份认证信息  
    public function userCenter()
    {
        $user_id = Users::getUserId();
        $user = Users::where("id", $user_id)->first(['id', 'phone', 'email']);
        if (empty($user)) {
            return $this->error("会员未找到");
        }
        $userreal = UserReal::where('user_id', $user_id)->first();

        if (empty($userreal)) {
            $user['review_status'] = 0;
            $user['name'] = '';
            $user['card_id'] = '';
        } else {
            $user['review_status'] = $userreal['review_status'];
            $user['name'] = $userreal['name'];
            $user['card_id'] = $userreal['card_id'];

        }

        if (!empty($user['card_id'])) {
            $user['card_id'] = mb_substr($user['card_id'], 0, 2) . '******' . mb_substr($user['card_id'], -2, 2);
        }
        return $this->success($user);
    }

    //专属海报信息
    public function posterBg()
    {
        $user_id = Users::getUserId();
        $user = Users::where("id", $user_id)->first(['id', 'extension_code']);
        if (empty($user)) {
            return $this->error("会员未找到");
        }
        $pics = InviteBg::all(['id', 'pic'])->toArray();

        $data['extension_code'] = $user['extension_code'];
        $data['share_url'] = Setting::getValueByKey('share_url', '');
        $data['pics'] = $pics;

        return $this->success($data);

    }

     //我的邀请分享
    public function share()
    {
        $user_id = Users::getUserId();
        $user = Users::where("id", $user_id)->first(['id', 'extension_code']);
        if (empty($user)) {
            return $this->error("会员未找到");
        }

        $data['share_title'] = Setting::getValueByKey('share_title', '');
        $data['share_content'] = Setting::getValueByKey('share_content', '');
        $data['share_url'] = Setting::getValueByKey('share_url', '');
        $data['extension_code'] = $user['extension_code'];

        return $this->success($data);

    }


    //退出  
    public function logout()
    {

        $user_id = Users::getUserId();
        $user = Users::find($user_id);

        if (empty($user)) {
            return $this->error("会员未找到");
        }
        //清除用户session
        session()->flush();
        session()->regenerate(); //重新生成一个新的session_id
        $token = Token::getToken();
        //删除当前token 
        Token::deleteToken($user_id, $token);
        return $this->success('退出登录成功');


    }

    public function vip()
    {
        $user_id = Users::getUserId(Input::get("user_id"));
        $password = Input::get('password', '');


        if (empty($password)) return $this->error("请输入支付密码");

        $vip = Input::get("vip");
        if (empty($user_id) || empty($vip)) {
            return $this->error("参数错误");
        }
        $user = Users::find($user_id);
        if (empty($user)) {
            return $this->error("会员未找到");
        }
        if ($user->vip >= $vip) {
            return $this->error("无需升级");
        }
        if ($vip == "2") {
            if ($user->vip == 1) {
                $money = 9000;
            } else {
                $money = 9999;
            }
        } else {
            $money = 999;
        }

        $wallet = UsersWallet::where("user_id", $user_id)
            ->where("token", Users::TOKEN_DEFAULT)
            ->select("id", "user_id", "password", "address", "balance", "lock_balance", "remain_lock_balance", "create_time", "wallet_name", "password_prompt")
            ->first();
        if (empty($wallet)) {
            return $this->error("暂无钱包");
        }
        if ($password != $wallet->password) {
            return $this->error("支付密码错误");
        }
        if ($wallet->balance < $money) {
            return $this->error("余额不足");
        }

        $walletn = UsersWallet::find($wallet->id);
        $data_wallet = [
            'balance_type' => AccountLog::UPDATE_VIP,
            'wallet_id' => $walletn->id,
            'lock_type' => 0,
            'create_time' => time(),
            'before' => $walletn->balance,
            'change' => -$money,
            'after' => bc_sub($walletn->balance, $money, 5),
        ];
        $user->vip = $vip;
        $walletn->balance = $walletn->balance - $money;
        $user->save();
        $walletn->save();
        AccountLog::insertLog(
            array(
                "user_id" => $user_id,
                "value" => -$money,
                "type" => AccountLog::UPDATE_VIP,
                "info" => "升级会员"
            ),
            $data_wallet
        );
        return $this->success("升级成功");
    }
    //提交虚拟币收货地址
    public function updateCurrencyAddress()
    {

    }
    public function updateAddress()
    {
        $address = Users::getUserId();

        $eth_address = trim(Input::get('eth_address'));
        if (empty($address) || empty($eth_address)) {
            return $this->error('参数错误');
        }
        $user = Users::find($address);
        if (empty($user)) {
            return $this->error('没有此用户');
        }

        if ($other = Users::where('eth_address', $eth_address)->first()) {
            if ($other->id != $user->id) {
                return $this->error('该地址别人已经绑定过了');
            }
        }
        try {
            $user->eth_address = $eth_address;
            $user->save();
            return $this->success('更新成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function getUserByAddress()
    {
        $user_id = Users::getUserId();
        if (empty($user_id))
            return $this->error("参数错误");
        $user = Users::where("id", $user_id)->first();
        if (empty($user)) {
            return $this->error("会员未找到");
        }
        if (empty($user->extension_code)) {
            $user->extension_code = Users::getExtensionCode();
            $user->save();
        }

        $wallet = UsersWallet::where("user_id", $user_id)
            ->where("token", Users::TOKEN_DEFAULT)
            ->select("id", "user_id", "address", "balance", "lock_balance", "remain_lock_balance", "create_time", "wallet_name", "password_prompt")
            ->first();
        $user->wallet = $wallet;
        return $this->success($user);
    }
    public function chatlist()
    {
        $user_id = Users::getUserId(Input::get('user_id', ''));
        if (empty($user_id)) return $this->error("参数错误");

        $user = Users::find($user_id);
        if (empty($user)) return $this->error("用户未找到");

        $chat_list = UserChat::orderBy('id', 'DESC')->paginate(20);

        $datas = $chat_list->items();

        krsort($datas);
        $return = array();
        foreach ($datas as $d) {
            array_push($return, $d);
        }
        return $this->success(array(
            "user" => $user,
            "chat_list" => [
                'total' => $chat_list->total(),
                'per_page' => $chat_list->perPage(),
                'current_page' => $chat_list->currentPage(),
                'last_page' => $chat_list->lastPage(),
                'next_page_url' => $chat_list->nextPageUrl(),
                'prev_page_url' => $chat_list->previousPageUrl(),
                'from' => $chat_list->firstItem(),
                'to' => $chat_list->lastItem(),
                'data' => $return,
            ]
        ));
    }
    public function sendchat()
    {
        $user_id = Users::getUserId(Input::get('user_id', ''));

        $content = Input::get('content', '');
        if (empty($user_id) || empty($content)) return $this->error("参数错误");

        $user = Users::find($user_id);
        if (empty($user)) return $this->error("会员未找到");

        $data["user_id"] = $user_id;
        $data["user_name"] = $user->account_number;
        $data["head_portrait"] = $user->head_portrait;
        $data["content"] = $content;
        $data["type"] = "1";


        try {
            $res = UserChat::sendChat($data);
            if ($res == "ok") {
                $user_chat = new UserChat();
                $user_chat->from_user_id = $user_id;
                $user_chat->to_user_id = 0;
                $user_chat->content = $content;
                $user_chat->type = 1;
                $user_chat->add_time = time();
                $user_chat->save();
                return $this->success("ok");
            } else {
                return $this->error("请重试");
            }
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function head_pic()//上传头像
    {
        $user_id = Users::getUserId();
        $head_portrait = Input::get('head_portrait', '');
        $user=Users::find($user_id);
        if(empty($user)){
            return $this->error("用户不存在");
        }else{
            $user->head_portrait=$head_portrait;
            $user->save();
            return $this->success("设置成功");
        }
    }

    //用户信息导入
    public function into_users()
    {
        $password = Input::get('password', '');
        $account_number = Input::get('account_number', '');
        $pay_password = Input::get('pay_password', '');
        $parent_id = Input::get('parent_id', '');//邀请人账户
        if (empty($parent_id) || empty($pay_password) || empty($password) || empty($password)) {
            return $this->error('请把参数填写完整');
        }
        //判断用户是否存在
        $user = Users::getByAccountNumber($account_number);
        if (!empty($user)) {
            return $this->error('用户已存在');
        }
        //判断推荐人是否存在
        $invit = Users::getByAccountNumber($parent_id);
        if (empty($invit)) {
            return $this->error('推荐用户不存在');
        }

        $users = new Users();
        $users->password = Users::MakePassword($password, 1);
        $users->pay_password = Users::MakePassword($pay_password, 0);
        $users->parent_id = $invit->id;
        $users->account_number = $account_number;
        $users->type = 1;
        $users->head_portrait = URL("mobile/images/user_head.png");
        $users->time = time();
        $users->extension_code = Users::getExtensionCode();
        DB::beginTransaction();
        try {
            $users->save();//保存到user表中
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
                $userWallet->save();//默认生成所有币种的钱包
            }
            DB::commit();
            return $this->success("注册成功");
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->error($ex->getMessage());
        }

    }
     //美丽链转入(imc) 
    public function into_tra()
    {
        $account_number = Input::get('account_number', '');
       // var_dump($account_number);die;
        $password = Input::get('password', '');
        $number = Input::get('number', '');
        $type = Input::get('type', '1'); ///type:0 法币交易，type:1币币交易，type:2杠杆交易
        //dump( $type);die;
        if (empty($account_number)) {
            return $this->error('转入账户不能为空');
        }
        if (empty($password)) {
            return $this->error('密码不能为空');
        }
        if (empty($number)) {
            return $this->error('转入数量不能为空');
        }
        $tra_user = Users::getByAccountNumber($account_number);
        if (empty($tra_user)) {
            return $this->error('用户未找到');
        }
        if ($tra_user->password != Users::MakePassword($password, $tra_user->type)) {
            return $this->error('用户密码错误');
        }
        //当前用户钱包信息
        $currency = Currency::where('name', 'IMC')->first();
        $waller_info = UsersWallet::where('currency', $currency->id)->where('user_id', $tra_user->id)->first();
      //dump( $waller_info);die;
        DB::beginTransaction();
        $data_wallet = [
             //'balance_type' =>  0,
            'wallet_id' => $waller_info->id,
            'lock_type' => 0,
            'create_time' => time(),
             //'before' => 0,
            'change' => $number,
             //'after' => 0,
        ];
        try {
            if ($type == 0) {
                $data_wallet['balance_type'] = 1;
                $data_wallet['before'] = $waller_info->legal_balance;
                $data_wallet['after'] = bc_add($waller_info->legal_balance, $number, 5);
                $waller_info->legal_balance = $waller_info->legal_balance + $number;
                $info = '美丽链法币交易余额转入';
                $type_info = AccountLog::INTO_TRA_FB;
            } else if ($type == 1) {
                $data_wallet['balance_type'] = 2;
                $data_wallet['before'] = $waller_info->change_balance;
                $data_wallet['after'] = bc_add($waller_info->change_balance, $number, 5);
                $waller_info->change_balance = $waller_info->change_balance + $number;
                $info = '美丽链币币交易余额转入';
                $type_info = AccountLog::INTO_TRA_BB;
            } else {
                $data_wallet['balance_type'] = 3;
                $data_wallet['before'] = $waller_info->lever_balance;
                $data_wallet['after'] = bc_add($waller_info->lever_balance, $number, 5);
                $waller_info->lever_balance = $waller_info->lever_balance + $number;
                $info = '美丽链杠杆交易余额转入';
                $type_info = AccountLog::INTO_TRA_GG;
            }
            $waller_info->save();
            //冻结余额

            $waller_info->save();
            AccountLog::insertLog([
                'user_id' => $tra_user->id,
                'value' => $number,
                'currency' => $currency->id,
                'info' => $info,
                'type' => $type_info,
            ], $data_wallet);
            DB::commit();
            return $this->success('转入成功');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->error($ex->getMessage());
        }


    }
     //转入记录
    public function into_tra_log()
    {
        $user_id = Users::getUserId();
        $list = AccountLog::whereIn("type", array(65, 66, 67))->where('user_id', $user_id)->orderBy('id', 'desc')->get()->toArray();
        return $this->success($list);
    }
     //修改密码
    public function e_pwd()
    {
        $account_number = Input::get('account_number', '');
        $password = Input::get('password', '');
        $type = Input::get('type', '1'); ///type:1登录密码，type:2支付密码
        if (empty($account_number)) {
            return $this->error('转入账户不能为空');
        }
        if (empty($password)) {
            return $this->error('密码不能为空');
        }
        $tra_user = Users::getByAccountNumber($account_number);
        if (empty($tra_user)) {
            return $this->error('用户未找到');
        }
        DB::beginTransaction();
        try {
            if ($type == 1) {
                $tra_user->password = Users::MakePassword($password, $tra_user->type);

            } else {
                $tra_user->pay_password = Users::MakePassword($password, $tra_user->type);
            }

            $tra_user->save();
            DB::commit();
            return $this->success('密码修改成功');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->error($ex->getMessage());
        }
    }

    /**
     * 交易所转入接口
     *
     * @return void
     */
    public function shiftToByExchange(Request $request)
    {
        try {
            $exchange_shift_to = DB::transaction(function () use ($request) {
                $appid = $request->input('appid', '');
                $address = $request->input('address', '');
                $number = $request->input('number', 0);
                $currency_name = $request->input('currency_name', '');
                $voucher_no = $request->input('voucher_no', '');
                $timestamp = $request->input('timestamp', 0);
                $nonce = $request->input('nonce', '');
                $signature = $request->input('signature', '');
                $ip = $request->ip();
                $now = Carbon::now();
                $validator = Validator::make($request->all(), [
                    'appid' => 'required|string|min:1',
                    'address' => 'required|string|min:1',
                    'number' => 'required|numeric|min:0.1',
                    'currency_name' => 'required|string|min:1',
                    'voucher_no' => 'required|string|min:1',
                    'timestamp' => 'required|integer|min:0',
                    'nonce' => 'required|string|min:6',
                    'signature' => 'required|string|min:1',
                ], [], [
                    'appid' => 'appid',
                    'address' => '钱包地址',
                    'number' => '数量',
                    'currency_name' => '币种名称',
                    'voucher_no' => '凭证号',
                    'timestamp' => '时间戳',
                    'nonce' => '随机口令',
                    'signature' => '签名',
                ]);

                throw_if($validator->fails(), new \Exception($validator->errors()->first()));

                throw_if($now->getTimestamp() < $timestamp, new \Exception('请求无效'));

                throw_if($now->subSeconds(10)->getTimestamp() > $timestamp, new \Exception('请求已过期'));

                $currency = Currency::where('name', $currency_name)
                    ->where('allow_game_exchange', 1)
                    ->first();

                throw_unless($currency, new \Exception('币种不存在'));

                $user_wallet = UsersWallet::whereHas('currencyCoin', function ($query) use ($currency_name) {
                        $query->where('name', $currency_name);
                    })->where('address', $address)
                    ->first();

                throw_unless($user_wallet, new \Exception('钱包地址未找到'));

                $api = AppApi::where('appid', $appid)
                    ->where('status', 1)
                    ->first();

                throw_unless($api, new \Exception('appid无效'));

                if ($api->bind_ip != '') {
                    $ip_list = explode(',', $api->bind_ip);
                    throw_if(!in_array($ip, $ip_list), new \Exception('IP无效'));
                }
                throw_unless($this->signatureCheck($request->all()), new \Exception('签名无效'));
                //验证凭证是否有效
                throw_unless($this->checkVoucher($voucher_no, $request->all()), new \Exception('凭证无效'));
                ExchangeShiftTo::unguard();
                $exchange_shift_to = ExchangeShiftTo::create([
                    'user_id' => $user_wallet->user_id,
                    'appid' => $appid,
                    'currency_id' => $currency->id,
                    'voucher_no' => $voucher_no,
                    'number' => $number,
                ]);
                throw_unless(isset($exchange_shift_to->id),  new \Exception('创建凭证失败'));
                /*
                //到账应该48小时才到
                $result = change_wallet_balance($user_wallet, 1, $number, AccountLog::GAME_SHIFT_TO, '游戏转入交易所,凭证号:' . $voucher_no);
                throw_if($result !== true, new \Exception($result));
                */
                return $exchange_shift_to;
            });
            return $this->success('提交成功,凭证号:' . $exchange_shift_to->id);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        } finally {
            ExchangeShiftTo::reguard();
        }
    }

    /**
     * 验证凭证是否有效
     *
     * @param string $voucher_no 凭证号
     * @param array $param 凭证的内容
     * @return boolean
     */
    public function checkVoucher($voucher_no, $param)
    {
        //请里应请求游戏的接口，查询对方凭证的状态与信息是否相符
        $http_client = new Client();
        return true;
    }

    /**
     * 验证签名
     *
     * @param array $param
     * @return bool
     */
    public function signatureCheck($param)
    {
        if (!isset($param['signature']) || !isset($param['appid'])) {
            return false;
        }
        $signature = $param['signature'];
        $content = $this->makeSignature($param);
        return $signature === $content;
    }

    public function makeSignature($param)
    {
        if (!isset($param['appid'])) {
            return false;
        }
        if (isset($param['signature'])) {
            unset($param['signature']);
        }
        $appid = $param['appid'];
        $api = AppApi::where('appid', $appid)
            ->where('status', 1)
            ->first();
        if (!$api) {
            return false;
        }
        ksort($param, SORT_STRING);
        $content = $appid . http_build_query($param) . $api->appsecret;
        return md5($content);
    }


    //用户授权码获取(添加代理商是需要用)
    public function authCode(){
        $user_id=Users::getUserId();
        if (Cache::has('authorization_code_'.$user_id)) {

            $code=Cache::get('authorization_code_'.$user_id);

        }else{
            //获取随机授权码
            $code=Users::generate_password(6);
            //缓存
            Cache::put('authorization_code_'.$user_id, $code,600);
        }

        return $this->success($code);

    }
}
