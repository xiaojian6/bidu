<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;
use App\Models\{AccountLog, Users};

class UnlockCurrentDeposit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unlock_current_deposit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '活期存币解锁并计算收益';

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
        $income = $row['num'] * $days * ($percent/365);
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
}
