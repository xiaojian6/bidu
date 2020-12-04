<?php

namespace App\Console\Commands;

use App\Models\AccountLog;
use App\Models\UsersWallet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Giftpse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gift_pse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每日USDT余额赠送PSE';

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
        $this->git_pse_by_usdt_banlance();
    }
    //根据当日usdt余额赠送pse，每100usdt赠送1pse。根据每日余额。
    public function git_pse_by_usdt_banlance()
    {
        DB::table("users_wallet")
            ->where("change_balance",">=",100)
            ->where(function($query){
                $query->where('currency','3')
                    ->orWhere(function($query){
                        $query->where('currency', '20');
                    });
            })
            ->orderBy('id')
            ->select("change_balance","user_id","currency")
            ->chunk(100, function ($user_wallet_lists) {
                // Process the records...
                foreach ($user_wallet_lists as $key => $val) {
                    if($val->change_balance > 0)
                    {
                        $num = $val->change_balance * 0.009;
                        $user_wallet = UsersWallet::where('user_id', $val->user_id)
                            ->lockForUpdate()
                            ->where('currency', 36)
                            ->first();
                        if ($user_wallet) {
                            if($val->currency == 3)
                            {
                                $memo = "USDT";
                            }else
                            {
                                $memo = "USDT(ERC20)";
                            }

                            //增PSE余额
                            change_wallet_balance($user_wallet, 2, $num, AccountLog::USET_BALANCE_GIFT_TO_PSE_BIBI, "每日".$memo."余额赠送PSE");
//                            $result_bibi = change_wallet_balance($user_wallet, 2, $num, AccountLog::USET_BALANCE_GIFT_TO_PSE_BIBI, "每日".$memo."余额赠送PSE");
//                            if($result_bibi !== true)
//                            {
//                                throw new \Exception($result_bibi);
//                            }
                        }
                    }
                }
            });
    }
}
