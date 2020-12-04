<?php

namespace App\Console\Commands;

use App\Models\AccountLog;
use App\Models\AirdropConfig;
use App\Models\UsersWallet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LockRelease extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'locked_release';

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
                        if ($result === true) {
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
}
