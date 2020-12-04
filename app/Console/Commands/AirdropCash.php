<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;
use App\Models\{AccountLog, Transaction, UsersWallet,AirdropConfig};

class AirdropCash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'airdrop_cash';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '空投兑现';

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
     * 空投兑现
     *
     * @return mixed
     */
    public function handle()
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
            if ($result === true) {
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
}
