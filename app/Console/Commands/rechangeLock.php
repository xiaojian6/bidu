<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\{AccountLog, Transaction, Users,UsersWallet};

class rechangeLock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rechange_lock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '充币锁仓';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $this->trans_to_lock_income();
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
}
