<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;
use App\Models\{AccountLog, Users, UsersWallet};


class RebackFixedDeposit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reback_fixed_deposit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定期存币生息自动返还';

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
//                        return $this->error($result);
                        throw new \Exception($result);
                    }else
                    {
                        //转出存币生息账户
                        $total = $iv['num'] + $iv['income'];
                        $result_ex = change_wallet_balance($userWallet, 5, -$total, AccountLog::BIBI_DEPOSIT_FIXED_RETURN, "定期存币生息到期自动转出");
                        if($result_ex !== true)
                        {
//                            return $this->error($result_ex);
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
//                                return $this->error($result_in);
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
}
