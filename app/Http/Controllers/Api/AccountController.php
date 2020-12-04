<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\Request;
use App\Models\{AccountLog, AirdropConfig, Transaction, Users, UsersWallet};

class AccountController extends Controller
{
    public function list()
    {
        $address = Users::getUserId(Input::get('address', ''));
        $limit = Input::get('limit', '12');
        $page = Input::get('page', '1');
        if (empty($address)) {
            return $this->error("参数错误");
        }
        $user = Users::where("id", $address)->first();
        if (empty($user)) {
            return $this->error("数据未找到");
        }
        $data = AccountLog::where("user_id", $user->id)->orderBy('id', 'DESC')->paginate($limit);
        return $this->success(array(
            "user_id" => $user->id,
            "data" => $data->items(),
            "limit" => $limit,
            "page" => $page,
        ));
    }

    public function show_profits(Request $request)
    {
        $user_id = Users::getUserId();
        $limit = $request->input('limit', 10);
        $prize_pool = AccountLog::whereHas('user', function ($query) use ($request) {
            $account_number = $request->input('account_number');
            if ($account_number) {
                $query->where('account_number', $account_number);
            }
        })->where(function ($query) use ($request) {
            //$scene = $request->input('scene', -1);
            $start_time = strtotime($request->input('start_time', null));
            $end_time = strtotime($request->input('end_time', null));
            //$scene != -1 && $query->where('scene', $scene);
            $start_time && $query->where('created_time', '>=', $start_time);
            $end_time && $query->where('created_time', '<=', $end_time);
        })->where("type", AccountLog::PROFIT_LOSS_RELEASE)->where("user_id", "=", $user_id)->orderBy('id', 'desc')->paginate($limit);

        return $this->success($prize_pool);
    }
    //手机号敏感处理
    public function encryptTel($tel = "")
    {
        $new_tel = substr_replace($tel, '****', 3, 4);
        return $new_tel;
    }

    /*
     * 获取充币列表排名
     * */
    public function get_rechage_order_lists()
    {
        //空投计算开始时间 0点
        $sTime = strtotime(date('Ymd'));
        //空投计算结束时间 22点
        $eTime  = strtotime(date('Ymd'))+ 79200;

        $limit = 20;
        $lists = DB::table('account_log')
            ->leftJoin('users', 'users.id', '=', 'account_log.user_id')
            ->limit($limit)
            ->select('user_id',DB::raw("sum(value) as value"),"users.account_number")
            ->where("account_log.type",AccountLog::ETH_EXCHANGE)
            ->whereBetween("created_time",[$sTime,$eTime])
            ->groupBy("user_id")
            ->orderBy("value","desc")
            ->orderBy("created_time","asc")
            ->get();
        if($lists)
        {
            $lists = json_decode(json_encode($lists),true);
            foreach ($lists as $ik => $iv)
            {
                $lists[$ik]['account_number'] = $this->encryptTel($iv['account_number']);
            }
        }
        // 获取查询日志
        return $this->success($lists);
    }

    public function air_drop_config()
    {
        $config = AirdropConfig::get_air_config();
        if(!$config)
        {
            return $this->error("空投数据未配置");
        }
        return $this->success($config);
    }
    public function air_drop_test()
    {
        $config = AirdropConfig::get_air_config();
        if(!$config)
        {
            return $this->error("空投数据未配置");
        }

        //空投计算开始时间 0点
        $sTime = strtotime(date('Ymd'));
        //空投计算结束时间 22点
        $eTime  = strtotime(date('Ymd'))+ 79200;

        $limit = 20;
        $lists = DB::table('account_log')
            ->where("currency",$config['cur_currency'])
            ->leftJoin('users', 'users.id', '=', 'account_log.user_id')
            ->limit($limit)
            ->select('user_id',DB::raw("sum(value) as value"),"users.account_number")
            ->whereBetween("created_time",[$sTime,$eTime])
            ->groupBy("user_id")
            ->orderBy("value","desc")
            ->orderBy("created_time","asc")
            ->get();
        if($lists)
        {
            //写入交易快照
            $lists_data = array();
            $sort = 1;
            foreach ($lists as $ik => $iv)
            {
                $l_val = json_decode( json_encode( $iv),true);
                $l_arr = array(
                    "user_id" => $l_val['user_id'],
                    "account_number" => $l_val['account_number'],
                    "total" => $l_val['value'],
                    "createTime" => time(),
                    "status" => 0,
                    "aridate" => strtotime(date('Ymd')),
                    "cur_currency" => $config['cur_currency'],
                    "air_currency" => $config['air_curency'],
                    "sort" => $sort,
                );

                array_push($lists_data,$l_arr);
                $sort++;
            }
            DB::table('airdrop_lists')
                ->insert($lists_data);
        }
    }
    public function make_airdrop()
    {
        //计算空投配置天数
        $config = AirdropConfig::get_air_config();
        if(!$config)
        {
            return $this->error("空投数据未配置");
        }
        $stime = $config['stime'];
        $etime = $config['endTime'];

        //活动天数
        $days = floor((strtotime($etime)-strtotime($stime))/86400);

        //平均值
        $avg_air_count = $config['total'] / $days;

        $limit = 20;
        $lists = DB::table('airdrop_lists')
            ->where("status",0)
            ->where("aridate",strtotime(date('Ymd')))
            ->limit($limit)
            ->orderBy("sort","asc")
            ->get();
        foreach ($lists as $ik => $iv)
        {
            $iv =  json_decode(json_encode($iv), true);
            //用户钱包模型
            $userWallet = UsersWallet::where("user_id", $iv['user_id'])
                ->where("currency", $iv['air_currency'])
                ->lockForUpdate()
                ->first();
            //计算空投奖励金额
            $num = $avg_air_count * $this->airdrop_precent($iv['sort']);

            //发放空投
            $result = change_wallet_balance($userWallet, 2, $num, AccountLog::BIBI_AIRDROP_RETURN, "空投奖励发放");
            if ($result) {
                $status['status'] = 1;
                //改变空投奖励状态
                DB::table('airdrop_lists')->where("id",$iv['id'])->update($status);
            }else
            {
                throw new \Exception($result);
            }
        }

    }

    public function airdrop_precent($sort = 1)
    {
        switch ($sort)
        {
            case 1:
                return 0.2;
                break;
            case 2:
                return 0.15;
                break;
            case 3:
                return 0.072;
                break;
            case 4:
                return 0.05;
                break;
            case 5:
                return 0.048;
                break;
            case 6:
                return 0.046;
                break;
            case 7:
                return 0.044;
                break;
            case 8:
                return 0.042;
                break;
            case 9:
                return 0.04;
                break;
            case 10:
                return 0.038;
                break;
            case 11:
                return 0.036;
                break;
            case 12:
                return 0.034;
                break;
            case 13:
                return 0.032;
                break;
            case 14:
                return 0.03;
                break;
            case 15:
                return 0.028;
                break;
            case 16:
                return 0.026;
                break;
            case 17:
                return 0.024;
                break;
            case 18:
                return 0.022;
                break;
            case 19:
                return 0.020;
                break;
            case 20:
                return 0.018;
                break;
            default:
                break;
        }
    }

    //锁仓释放
    public function locked_release()
    {
       //查询释放配置
        $config = AirdropConfig::get_locked_scale();
        if($config)
        {
            $locked_scale = $config['lock_scale'];

            //查询用户账户 进行释放

            $user_wallet = DB::table('users_wallet')
                ->select('user_id','currency','change_balance','locked_balance')
                ->get();
            if($user_wallet)
            {
                foreach ( $user_wallet as $ik => $iv)
                {
                    $l_val = json_decode( json_encode( $iv),true);
                    if($l_val['locked_balance'])
                    {
                        //表示存在锁仓余额 开始释放
                        //用户钱包模型
                        $userWallet = UsersWallet::where("user_id", $l_val['user_id'])
                            ->where("currency", $l_val['currency'])
                            ->lockForUpdate()
                            ->first();
                        //减少锁仓余额
                        $num = $l_val['locked_balance'] * $locked_scale;
                        $result = change_wallet_balance($userWallet, 4, -$num, AccountLog::BIBI_LOCKED_RELEASE, "锁仓账户释放");
                        if ($result) {
                            //增加币币余额
                            $result_bibi = change_wallet_balance($userWallet, 2, $num, AccountLog::ADD_BIBI_LOCKED_RELEASE, "锁仓账户释放，币币余额增加");
                            if($result_bibi !== true)
                            {
                                throw new \Exception($result);
                            }
                        }else
                        {
                            throw new \Exception($result);
                        }

                    }
                }
            }
        }
    }
    //开始或结束空投
    public function check_airdrop()
    {
        //计算空投配置天数
        $config = AirdropConfig::get_air_config();
        if($config)
        {
            $stime = $config['stime'];
            $etime = $config['endTime'];
            if($stime <= time())
            {
                $status = 1;
            }elseif($etime > time())
            {
                $status = 2;
            }
            AirdropConfig::chage_status($status);
        }
    }
    //根据当日usdt余额赠送pse，每100usdt赠送1pse。根据每日余额。
    public function git_pse_by_usdt_banlance()
    {
        //step1 check the banlance of usdt wallet
        DB::table("users_wallet")->where("currency",3)->where("user_id",106)->where("change_balance",">",0)->orderBy('id')->select("change_balance","user_id")->chunk(100, function ($user_wallet_lists) {
            // Process the records...
            foreach ($user_wallet_lists as $key => $val) {
               if($val->change_balance > 0)
               {
                   $num = $val->change_balance * 0.01;

                   $user_wallet = UsersWallet::where('user_id', $val->user_id)
                       ->lockForUpdate()
                       ->where('currency', 36)
                       ->first();
                   if (!$user_wallet) {
                       throw new \Exception('钱包不存在');
                   }

                   //增PSE余额
                   $result_bibi = change_wallet_balance($user_wallet, 2, $num, AccountLog::USET_BALANCE_GIFT_TO_PSE_BIBI, "每日USDT余额赠送PSE");
                   if($result_bibi !== true)
                   {
                       throw new \Exception($result_bibi);
                   }
               }
            }
        });
    }
}
