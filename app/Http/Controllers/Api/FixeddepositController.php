<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\Request;
use App\Models\{AccountLog, AirdropConfig, Transaction, Users, UsersWallet};

class FixeddepositController extends Controller
{
    //获取存币币种列表
    public function get_currency_lists()
    {
       $lists = DB::table("currency")
           ->select("id","name")
           ->where("is_display","1")
           ->get();
       return $this->success($lists);
    }
    //获取存币期限
    public function get_deposit_percent()
    {
        $fixed_lists = DB::table("percent")
            ->select("id","desc","percent")
            ->where("type","1")
            ->get();
        $current_lists = DB::table("percent")
            ->select("id","desc","percent")
            ->where("type","2")
            ->get();
        $fixed_lists = json_decode(json_encode($fixed_lists),true);
        $current_lists = json_decode(json_encode($current_lists),true);
        foreach ($fixed_lists as $ik => $iv)
        {
            $fixed_lists[$ik]['percent'] = $iv['percent'] * 100 . "%";
        }
        foreach ($current_lists as $iik => $iiv)
        {
            $current_lists[$iik]['percent'] = $iiv['percent'] * 100 . "%";
        }
        $data['fixed'] = $fixed_lists;
        $data['current'] = $current_lists;
        return $this->success($data);
    }
    //定期存币
    public function fixed_deposit_opreation(Request $request)
    {
        $user_id = Users::getUserId();
        if (empty($user_id)) {
//            $user_id = 77;
            return $this->error('请先登录后在操作!');
        }

        $currency  = $request->get("currency");
        $num  = $request->get("num");
        $percent  = $request->get("percent");

        $deposit_type = $request->get("deposit_type");
        //定期存币
        if($deposit_type == 1)
        {
            $balance_type = 5;
            $log_type = AccountLog::BIBI_DEPOSIT_FIXED_SELE;
            $memo = "币币余额转入定期存币生息账户";
        }
        else if($deposit_type == 2)
        {
            $balance_type = 6;
            $log_type = AccountLog::BIBI_DEPOSIT_CURRENT_SELE;
            $memo = "币币余额转入活期存币生息账户";
        }
        $c_time = time();
        //获取返还币种类型
//        $return_currency  = $this->get_deposit_return($deposit_type);
        /*
         * 2020-07-20 应客户要求 返回币种为本位币
         * */
        $return_currency  = $currency;

        //用户钱包模型
        $userWallet = UsersWallet::where("user_id", $user_id)
            ->where("currency", $currency)
            ->lockForUpdate()
            ->first();


        //先转出  后转入
        $ex_result = change_wallet_balance($userWallet, 2, -$num, AccountLog::BIBI_DEPOSIT_EXPORE, "币币余额转出至存币生息账户");
        if($ex_result === true)
        {
            //收币转入存币生息账户中
            $result = change_wallet_balance($userWallet, $balance_type, $num, $log_type, $memo);
            if ($result === true) {
                //计算到期时间
                $limit_time = $this->get_percent_limitTime($deposit_type,$percent,$c_time);

                //加入定期存币生息账户
                $ins_data = array(
                    "user_id" => $user_id,
                    "currency" => $currency,
                    "ex_currency" => $return_currency,
                    "type" => $deposit_type,
                    "num" => $num,
                    "per_id" => $percent,
                    "create_time" => time(),
                    "limit_time" => $limit_time,
                    "status" => 0,
                    "income" => 0.000000,
                );
                $res = $this->deposit_insert($ins_data);
                if($res)
                {
                    return $this->success("存币成功!");
                }else
                {
                    return $this->error("存币失败!");
                }
            }else
            {
                return $this->error($result);
                throw new \Exception($result);
            }
        }else
        {
            return $this->error($ex_result);
            throw new \Exception($ex_result);
        }
    }
    //获取存币生息记录
    public function get_deposit_lists(Request $request)
    {
        $user_id = Users::getUserId();
        if (empty($user_id)) {
            return $this->error('请先登录后在操作!');
        }
        $deposit_type = $request->get("deposit_type");

        $r_lists = DB::table("deposit_lists")
            ->select("deposit_lists.*","currency.name")
            ->leftJoin('currency', 'currency.id', '=', 'deposit_lists.currency')
            ->where("deposit_lists.user_id",$user_id)
            ->where("deposit_lists.type",$deposit_type)
            ->get();
        if($r_lists)
        {
            $r_lists = json_decode(json_encode($r_lists),true);
            foreach ($r_lists as $ik => $iv)
            {
                $iv = json_decode(json_encode($iv),true);
                $r_lists[$ik]['create_time'] = date( "Y-m-d H:i",$iv['create_time']);
                $r_lists[$ik]['limit_time'] = date( "Y-m-d H:i",$iv['limit_time']);
            }
        }
        return $this->success($r_lists);
    }
    //活期解锁
    public function unlock_current_deposit()
    {
        $curr_lists = DB::table("deposit_lists")
            ->where("type",2)
            ->where("status","<>",2)
            ->get();
        if($curr_lists)
        {
            $curr_lists = json_decode(json_encode($curr_lists),true);
            foreach ($curr_lists as $ik => $iv) {
                $iv = json_decode(json_encode($iv),true);
                if($iv['limit_time']  < time())
                {
                    //无需重复改变改变状态
                    if($iv['status'] != 1)
                    {
                      $this->change_deposit_record_status($iv['id'],1);
                    }
                    //计算收益
                    $this->calculate_income($iv);
                }
            }
        }
    }
    //计算活期收益
    public function calculate_income($row)
    {
        //查询奖励比例
        $percent = $this->get_percent_by_id($row['per_id']);
        $days =floor(( time() - $row['limit_time'] ) / 86400);
        $income = $row['num'] * $days * ($percent / 365);
        $sd_income['income'] = $income;
        //收益存储
        DB::table("deposit_lists")
        ->where("id",$row['id'])
        ->update($sd_income);
    }
    //存币转出
    public function ex_current_deposit_record(Request $request)
    {
        $user_id = Users::getUserId();
        if (empty($user_id)) {
//            $user_id = 77;
            return $this->error('请先登录后在操作!');
        }
        $r_id = $request->get("r_id");
        //查询记录
        $r_row_item = DB::table("deposit_lists")
            ->where("id",$r_id)
            ->first();
        if($r_row_item)
        {
            $r_row_item = json_decode(json_encode($r_row_item),true);
            //收益归拢 BIBI_CURRENT_DEPOSIT_INCOME_RETURN
            //用户钱包模型
            $userWallet = UsersWallet::where("user_id", $user_id)
                ->where("currency", $r_row_item['currency'])
                ->lockForUpdate()
                ->first();

            $result = change_wallet_balance($userWallet, 6, $r_row_item['income'], AccountLog::BIBI_CURRENT_DEPOSIT_INCOME_RETURN, "活期存币生息归拢");
            if ($result !== true) {
                return $this->error($result);
                throw new \Exception($result);
            }else
            {
                //转出存币生息账户
                $total = $r_row_item['num'] + $r_row_item['income'];
                $result_ex = change_wallet_balance($userWallet, 6, -$total, AccountLog::BIBI_DEPOSIT_CURRENT_RETURN, "活期存币生息手动转出");
                if($result_ex !== true)
                {
                    return $this->error($result_ex);
                    throw new \Exception($result_ex);
                }else
                {
                    //转入币币账户
                    //用户钱包模型
                    $in_userWallet = UsersWallet::where("user_id", $user_id)
                        ->where("currency", $r_row_item['ex_currency'])
                        ->lockForUpdate()
                        ->first();
                    $result_in = change_wallet_balance($in_userWallet, 2, $total, AccountLog::BIBI_DEPOSIT_CURRENT_IN, "活期存币生息手动转入，返还本息");
                    if($result_in !== true)
                    {
                        return $this->error($result_in);
                        throw new \Exception($result_in);
                    }else
                    {
                        //改变转出状态
                        $this->change_deposit_record_status($r_row_item['id'],2);
                        return $this->success("转出成功!");
                    }
                }
            }
        }
    }

    //计算定期收益
    public function update_fixed_deposit()
    {
        $fixed_lists = DB::table("deposit_lists")
            ->where("type",1)
            ->where("status","<>",1)
            ->get();
        if($fixed_lists)
        {
            $fixed_lists = json_decode(json_encode($fixed_lists),true);
            foreach ($fixed_lists as $ik => $iv) {
                $iv = json_decode(json_encode($iv),true);
                //计算收益
                if($iv['create_time'] <  time())
                {
                    //计算收益
                    $this->calculate_fixedincome($iv);
                }
                if($iv['limit_time'] <  time())
                {
                    //到期返还
                    //收益归拢 BIBI_CURRENT_DEPOSIT_INCOME_RETURN
                    //用户钱包模型
                    $userWallet = UsersWallet::where("user_id", $iv['user_id'])
                        ->where("currency", $iv['currency'])
                        ->lockForUpdate()
                        ->first();

                    $result = change_wallet_balance($userWallet, 5, $iv['income'], AccountLog::BIBI_FIXED_DEPOSIT_INCOME_RETURN, "定期存币生息归拢");
                    if ($result !== true) {
                        return $this->error($result);
                        throw new \Exception($result);
                    }else
                    {
                        //转出存币生息账户
                        $total = $iv['num'] + $iv['income'];
                        $result_ex = change_wallet_balance($userWallet, 5, -$total, AccountLog::BIBI_DEPOSIT_FIXED_RETURN, "定期存币生息到期自动转出");
                        if($result_ex !== true)
                        {
                            return $this->error($result_ex);
                            throw new \Exception($result_ex);
                        }else
                        {
                            //转入币币账户
                            //用户钱包模型
                            $in_userWallet = UsersWallet::where("user_id", $iv['user_id'])
                                ->where("currency", $iv['ex_currency'])
                                ->lockForUpdate()
                                ->first();
                            $result_in = change_wallet_balance($in_userWallet, 2, $total, AccountLog::BIBI_DEPOSIT_FIXED_IN, "定期存币生息自动转入，返还本息");
                            if($result_in !== true)
                            {
                                return $this->error($result_in);
                                throw new \Exception($result_in);
                            }else
                            {
                                //改变转出状态
                                $this->change_deposit_record_status($iv['id'],1);
                            }
                        }
                    }
                }
            }
        }
    }

    //计算活期收益
    public function calculate_fixedincome($row)
    {
        //查询奖励比例
        $percent = $this->get_fiex_percent($row['per_id']);
        $days =floor(( time() - $row['create_time'] ) / 86400);
        $income = $row['num'] * $days * $percent;
        $sd_income['income'] = $income;
        //收益存储
        DB::table("deposit_lists")
            ->where("id",$row['id'])
            ->update($sd_income);
    }

    //改变记录状态
    public function change_deposit_record_status($id = 0,$sta = 0)
    {
        $status['status'] = $sta;
         DB::table("deposit_lists")
        ->where("id",$id)
        ->update($status);
    }
    //查询利率
    public function get_percent_by_id($id = 0)
    {
        return DB::table("percent")
            ->where("id",$id)
            ->value("percent");
    }
    //计算定期利率
    public function get_fiex_percent($id = 0)
    {
            $fiex_row = DB::table("percent")
            ->where("id",$id)
            ->first();
        $fiex_row = json_decode(json_encode($fiex_row),true);
        if($fiex_row['type'] == 1)
        {
            $percent = $fiex_row['percent'] / ($fiex_row['time'] * 30);
            return $percent;
        }
    }
    //查询利率和天数
    //根据利率ID 计算 到期时间和利率
    public function get_percent_limitTime($type = 1,$perid = 0,$time = 0)
    {
        $row_data = DB::table("percent")
            ->where("type",$type)
            ->where("id",$perid)
            ->first();
        if($row_data)
        {
            if($type == 1)
            {
                //按月算
                $row_data = json_decode(json_encode($row_data),true);
                $limit_time =  strtotime("+".$row_data['time']."months",$time);
            }
            else if($type == 2)
            {
                //按天数
                $row_data = json_decode(json_encode($row_data),true);
                $limit_time =  strtotime("+1day",$time);
            }
            return $limit_time;
        }
    }
    //定期 活期 转入
    public function deposit_insert($insert_data)
    {
        return DB::table("deposit_lists")
            ->insert($insert_data);
    }
    //获取周期利率
    public function get_time_scale($type,$percent)
    {
        $account = DB::table("users")
            ->where("type",$type)
            ->where("percent",$percent)
            ->value("account_number");
        return $account;
    }
    //根据ID查询用户账户
    public function get_user_accout_by_id($user_id = 0)
    {
        $account = DB::table("users")
            ->where("id",$user_id)
            ->value("account_number");
        return $account;
    }
    public function get_deposit_return($type = 1)
    {
        $currency = DB::table("deposit_return")
            ->where("type",$type)
            ->value("currency");
        return $currency;
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

    //查询币种账户存币生息余额和收益
    public function get_user_fixed_deposit_total(Request $request)
    {
        $user_id = Users::getUserId();
        if (empty($user_id)) {
            return $this->error('请先登录后在操作!');
        }
        $currcncy = $request->get("currency");
        $total_num = DB::table("deposit_lists")
        ->where("currency",$currcncy)
        ->where("user_id",$user_id)
            ->where("type",1)
        ->where("status",0)
        ->sum("num");

        $total_income = DB::table("deposit_lists")
            ->where("currency",$currcncy)
            ->where("user_id",$user_id)
            ->where("type",1)
            ->where("status",0)
            ->sum("income");

        $total = $total_num + $total_income;

        $res_d['total'] = $total;
        $res_d['num'] = $total_num;
        $res_d['income'] = $total_income;
        return $this->success($res_d);

    }
    //查询币种账户存币生息余额和收益
    public function get_user_current_deposit_total(Request $request)
    {
        $user_id = Users::getUserId();
        if (empty($user_id)) {
            return $this->error('请先登录后在操作!');
        }
        $currcncy = $request->get("currency");
        $total_num = DB::table("deposit_lists")
            ->where("currency",$currcncy)
            ->where("user_id",$user_id)
            ->where("type",2)
            ->where("status","<>",2)
            ->sum("num");

        $total_income = DB::table("deposit_lists")
            ->where("currency",$currcncy)
            ->where("user_id",$user_id)
            ->where("type",2)
            ->where("status","<>",2)
            ->sum("income");

        $total = $total_num + $total_income;

        $res_d['total'] = $total;
        $res_d['num'] = $total_num;
        $res_d['income'] = $total_income;
        return $this->success($res_d);
    }
    //获取存币利率配置
    public function get_rechange_config()
    {
        $rechanges = DB::table("rechange_config")
        ->select("id","t_precent","t_rate","currencys")
        ->where("config","rechange")
        ->get()->toArray();
        return $rechanges;
    }
    //充值币种后转入锁仓账户
    public function trans_to_lock_income()
    {
        //获取当前系统配置
        $configs = $this->get_rechange_config();
        if($configs && $configs[0])
        {
            $re_configs_params = $configs[0];
        }
        if($re_configs_params)
        {
            $currency_arr = json_decode($re_configs_params->currencys);
            $t_precent = $re_configs_params->t_precent;
            $t_rate = $re_configs_params->t_rate;
            $dateStr = date('Y-m-d', time());
            $timestamp0 = strtotime($dateStr);

            //当日24点的时间
            $timestamp24 = strtotime($dateStr) + 86400;

            //获取当日冲币用户
            $rechange_list  = DB::table("account_log")
                ->whereIn("currency",$currency_arr)
                ->where("type",200)
                ->where("status",0)
                ->whereBetween("created_time",[$timestamp0,$timestamp24])
                ->get();
            if($rechange_list)
            {
                $rechange_lists = json_decode(json_encode($rechange_list),true);
                foreach ($rechange_lists as $ik => $iv)
                {
                    $this->user_wallet_trans_to_locked($iv);
                    //转移资产
                }
            }
        }
    }
    //资产锁仓
    public function user_wallet_trans_to_locked($iv)
    {
            //用户钱包模型
        $userWallet = UsersWallet::where("user_id", $iv['user_id'])
        ->where("currency", $iv['currency'])
        ->lockForUpdate()
        ->first();

        $num = $iv['value'];
        //减少交易币余额
        $result_move = change_wallet_balance($userWallet, 2, -$num, AccountLog::RECHANGE_TRANS_FROM_BIBIWALLET, "充币成功，自动转出至锁仓账户");

        if ($result_move === true) {
            //转移至锁仓账户
            $result = change_wallet_balance($userWallet, 4, $num, AccountLog::RECHANGE_TRANS_TO_LOCKED, "充币成功，自动转入锁仓账户");
            if ($result === true) {
                //加入记录
                $this->trans_to_lock_wallet($iv);
            }else
            {
//                throw new \Exception($result);
            }

        }else
        {
//            throw new \Exception($result_move);
        }

    }
    //加入锁仓记录
    public function trans_to_lock_wallet($data)
    {
        $ins_data = array(
            "user_id"=>$data['user_id'],
            "currency"=>$data['currency'],
            "lock_num"=>$data['value'],
            "created_time"=>time(),
            "last_release_time"=>time(),
        );
        $res = DB::table('rechange_lists')->insert($ins_data);
        if($res)
        {
            return $this->change_logs_status($data['id']);
        }
    }
    //改变表状态
    public function change_logs_status($id)
    {
        $status['status'] = 1;
        //改变空投奖励状态
        return DB::table('account_log')->where("id",$id)->update($status);
    }

    //锁仓每日资产释放
    public function locked_wallet_released()
    {
        //获取当前系统配置
        $t_precent = 0;
        $t_rate = 0;
        $configs = $this->get_rechange_config();
        if($configs && $configs[0])
        {
            $re_configs_params = $configs[0];
        }
        if($re_configs_params) {
            $t_precent = $re_configs_params->t_precent;
            $t_rate = $re_configs_params->t_rate;
        }
        //获取当日冲币用户
        $release_lists  = DB::table("rechange_lists")
            ->get();
        if($release_lists)
        {
            $release_lists = json_decode(json_encode($release_lists),true);
            foreach( $release_lists as $ik => $iv )
            {
                if( $iv['created_time'] == $iv['last_release_time'])
                {
                    //第一次释放
                    $this->locked_wallet_balance_released($iv,$t_precent,$t_rate);
                }else
                {
                    //每日释放
                    if(time() - $iv['last_release_time'] >=  24 * 60 * 60)
                    {
                        $this->locked_wallet_income_released($iv,$t_precent,$t_rate);
                    }
                }
            }
        }
    }

    //第一次释放
    public function locked_wallet_balance_released($iv,$t_precent,$t_rate)
    {

        $userWallet = UsersWallet::where("user_id", $iv['user_id'])
            ->where("currency", $iv['currency'])
            ->lockForUpdate()
            ->first();

        $num = $iv['lock_num'] * $t_precent;
        //减少锁定余额
        $result_move = change_wallet_balance($userWallet, 4, -$num, AccountLog::RECHANGE_WALLET_LOCKED_RELEASED, "锁仓账户释放");

        if ($result_move === true) {
            //释放到交易币余额
            $result = change_wallet_balance($userWallet, 2, $num, AccountLog::RECHANGE_WALLET_LOCKED_RELEASED_TO_BIBI, "锁仓账户释至币币账户");
            if ($result === true) {
                //改变锁仓余额
                //type 0每日交易额度释放  1 年利率奖励
                $this->add_to_locked_release_lists($iv,0,$t_precent,$t_rate);
            }else
            {
//                throw new \Exception($result);
            }

        }else
        {
//            throw new \Exception($result_move);
        }
    }
    //每日释放
    public function locked_wallet_income_released($iv,$t_precent,$t_rate)
    {

        $userWallet = UsersWallet::where("user_id", $iv['user_id'])
            ->where("currency", $iv['currency'])
            ->lockForUpdate()
            ->first();

        $num = $iv['lock_num'] * $t_precent;
        //减少锁定余额
        $result_move = change_wallet_balance($userWallet, 4, -$num, AccountLog::RECHANGE_WALLET_LOCKED_RELEASED, "锁仓账户释放");

        if ($result_move === true) {
            //释放到交易币余额
            $result = change_wallet_balance($userWallet, 2, $num, AccountLog::RECHANGE_WALLET_LOCKED_RELEASED_TO_BIBI, "锁仓账户释至币币账户");
            if ($result === true) {
                //改变锁仓余额
                //type 0每日交易额度释放  1 年利率奖励
                $this->add_to_locked_release_lists($iv,0,$t_precent,$t_rate);

                //释放年利率
                //年利率奖励
                $rate_num = $iv['lock_num'] * ($t_rate/365);
                $result_rate = change_wallet_balance($userWallet, 2, $rate_num, AccountLog::RECHANGE_WALLET_LOCKED_RELEASED_YEAR_RATE, "年利率奖励释放");
                if($result_rate === true)
                {
                    $this->add_to_locked_release_lists($iv,1,$t_precent,$t_rate);
                }

            }else
            {
//                throw new \Exception($result);
            }

        }else
        {
//            throw new \Exception($result_move);
        }
    }


    //加入锁仓记录
    public function add_to_locked_release_lists($data,$type,$t_precent,$t_rate)
    {
        $lock_num = 0;
        $release_num = $data['lock_num'] * $t_precent;
        if($type == 0)
        {
            $lock_num = $data['lock_num'] - $data['lock_num'] * $t_precent;
            $release_num =  $data['lock_num'] * $t_precent;
        }else
        {
            $lock_num = $data['lock_num'] - $data['lock_num'] * ($t_rate/365);
            $release_num =  $data['lock_num'] * ($t_rate/365);
        }
        $update_data = array(
            "lock_num"=> $data['lock_num'] - $data['lock_num'] * $t_precent ,
            "last_release_time"=>time(),
        );
        $update_res = DB::table('rechange_lists')
            ->where('id', $data['id'])
            ->update($update_data);

        if($update_res)
        {
            $ins_data = array(
                "userid"=>$data['currency'],
                "release_num"=>$release_num,
                "create_time"=>time(),
                "type"=>$type,
            );
          DB::table('rechange_release_lists')->insert($ins_data);
        }

    }



}
