<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;
use App\Models\{AccountLog, Transaction, Users,AirdropConfig};

class StartAirdrop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check_airdrop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '开始或者结束空投';

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
            $sta['status'] = $status;
            DB::table('airdrop_config')->where("config","airdrop")->update($sta);
        }
    }
}
