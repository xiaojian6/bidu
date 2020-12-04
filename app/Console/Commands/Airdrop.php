<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;
use App\Models\{AccountLog, Transaction, Users,AirdropConfig};

class Airdrop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'airdrop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '空投快照';

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
            ->leftJoin('users', 'users.id', '=', 'account_log.user_id')
            ->limit($limit)
            ->select('user_id',DB::raw("sum(value) as value"),"users.account_number")
            ->where("currency",$config['cur_currency'])
            ->where("account_log.type",AccountLog::ETH_EXCHANGE)
            ->whereBetween("created_time",[$sTime,$eTime])
            ->groupBy("user_id")
            ->orderBy("value","desc")
            ->orderBy("created_time","asc")
            ->get()->toArray();
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
}
