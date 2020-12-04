<?php

namespace App\Console\Commands;

use App\Currency;
use Illuminate\Console\Command;
use App\Models\Users;

use App\Models\AccountLog;

use App\Models\UsersWallet;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\BlockChain\Coin\BaseCoin;
use App\BlockChain\Coin\CoinManager;
use App\ChainHash;
use App\LbxHash;
use App\Models\TransactionComplete;
use App\Models\Setting;


class ReturnTransTate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'return:transrete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '币币交易手续费返佣';

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

        //is_return_lock   0未处理  1处理过
        $orders["out"] = TransactionComplete::where("is_return_deal",0)->groupBy('from_user_id','legal')->select(['id', 'user_id','from_user_id', 'number', 'create_time', 'currency','legal','in_fee','out_fee'])
            ->selectRaw('sum(`out_fee`) as `out_in_fee`')->get();


//        var_dump($orders->toArray());die;

        $bibi_rate=Setting::getValueByKey("bibi_rate")/100;

        //in_fee买入的手续费是currency_id的，out_fee卖出的是legal_id的手续费
        foreach ($orders["out"] as $key=>$val)
        {

            $this_user[$key]=Users::find($val->from_user_id);
            if(!empty($this_user[$key]->id))//该用户存在
            {
                $this_parent_user[$key]=Users::find($this_user[$key]->parent_id);//父级用户
                if(!empty($this_parent_user[$key]->id))//父级用户存在
                {
                    $number[$key]=bc_mul($bibi_rate,$val->out_in_fee,8);
                    if($number[$key]>0){
                        $father_user_wallet[$key]=UsersWallet::where("user_id",$this_parent_user[$key]->id)->where("currency",$val->legal)->first();
                        //1.法币,2.币币交易,3.杠杆交易
                        $change_result = change_wallet_balance($father_user_wallet[$key], 2, $number[$key], AccountLog::BIBI_RATE_RETURN, '币币交易手续费返佣');
                        $this_list_update=TransactionComplete::where("user_id",$val->user_id)->where("legal",$val->legal)->update(['is_return_deal' => 1]);

                    }else{
                        continue;
                    }

                }else{
                    $this_list_update=TransactionComplete::where("user_id",$val->user_id)->where("legal",$val->legal)->update(['is_return_deal' => 1]);
                }
            }
            else{
                $this_list_update=TransactionComplete::where("user_id",$val->user_id)->where("legal",$val->legal)->update(['is_return_deal' => 1]);
            }
        }



        $orders["in"] = TransactionComplete::where("is_return_deal",0)->groupBy('user_id','currency')->select(['id', 'user_id','from_user_id', 'number', 'create_time', 'currency','legal','in_fee','out_fee'])
            ->selectRaw('sum(`in_fee`) as `all_in_fee`')->get();
//        var_dump($orders["in"]->toArray());die;
        foreach ($orders["in"] as $key=>$val)
        {
//            var_dump(1);die;
            $this_user[$key]=Users::find($val->user_id);
            if(!empty($this_user[$key]->id))//该用户存在
            {
//                var_dump($this_user[$key]->parent_id);die;
                $this_parent_user[$key]=Users::find($this_user[$key]->parent_id);//父级用户
                if(!empty($this_parent_user[$key]->id))//父级用户存在
                {
                    $number[$key]=bc_mul($bibi_rate,$val->all_in_fee,8);
//                    var_dump($number[$key]);die;

                    if($number[$key]>0){
                        $father_user_wallet[$key]=UsersWallet::where("user_id",$this_parent_user[$key]->id)->where("currency",$val->currency)->first();

//                        if($val->user_id=77){
//                            var_dump($this_parent_user[$key]->id);
//                            var_dump($val->toArray());
//                            var_dump($number[$key]);die;
//
//                        }


                        //1.法币,2.币币交易,3.杠杆交易
                        $change_result = change_wallet_balance($father_user_wallet[$key], 2, $number[$key], AccountLog::BIBI_RATE_RETURN, '币币交易手续费返佣');
                        $this_list_update=TransactionComplete::where("user_id",$val->user_id)->where("currency",$val->currency)->update(['is_return_deal' => 1]);
//                        foreach ($this_list_update as $a=>$aval )
//                        {
//                            $aval->is_return_deal=1;
//                            $aval->save();
//                        }
                    }else{
                        continue;
                    }

                }else{
                    $this_list_update=TransactionComplete::where("user_id",$val->user_id)->where("currency",$val->currency)->update(['is_return_deal' => 1]);
                }
            }
            else{
                $this_list_update=TransactionComplete::where("user_id",$val->user_id)->where("currency",$val->currency)->update(['is_return_deal' => 1]);
            }
        }



    }
}
