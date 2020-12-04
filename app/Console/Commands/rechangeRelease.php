<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\{AccountLog, Transaction, Users,UsersWallet};

class rechangeRelease extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rechange_release';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '锁仓释放';

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
        $this->locked_wallet_released();
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
        if($num >= 10000)
        {
            $num = 10000;
        }
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
        if($num >= 10000)
        {
            $num = 10000;
        }
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
        if($release_num >= 10000)
        {
            $release_num = 10000;
        }

        if($type == 0)
        {
            if($data['lock_num'] * $t_precent >= 10000)
            {
                $lock_num = $data['lock_num'] - 10000;
            }else
            {
                $lock_num = $data['lock_num'] - $data['lock_num'] * $t_precent;
            }

            $release_num =  $data['lock_num'] * $t_precent;
            if($release_num >= 10000)
            {
                $release_num = 10000;
            }
        }else if($type == 1)
        {
            $lock_num = $data['lock_num'] - $data['lock_num'] * ($t_rate/365);
            $release_num =  $data['lock_num'] * ($t_rate/365);
        }

        $update_data = array(
            "lock_num"=> $lock_num ,
            "last_release_time"=>time(),
        );
        $update_res = DB::table('rechange_lists')
            ->where('id', $data['id'])
            ->update($update_data);

        if($update_res || $type == 1)
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
    //获取存币利率配置
    public function get_rechange_config()
    {
        $rechanges = DB::table("rechange_config")
            ->select("id","t_precent","t_rate","currencys")
            ->where("config","rechange")
            ->get()->toArray();
        return $rechanges;
    }
}
