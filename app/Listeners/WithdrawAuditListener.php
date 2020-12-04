<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

use App\Events\WithdrawAuditEvent;
use App\DAO\GoChainDAO;
use Illuminate\Support\Carbon;

class WithdrawAuditListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  WithdrawAuditEvent  $event
     * @return void
     */
    public function handle(WithdrawAuditEvent $event)
    {
        $withdraw = $event->withdraw;
        $withdraw->refresh();
        if ($withdraw->status == 2) {
            // 提币通过
            if (bc_comp($withdraw->real_number, '0') <= 0 || empty($withdraw->verificationcode)) {
                throw new \Exception('提币信息状态异常');
            }
            if ($withdraw->use_chain_api == 1) {
                //调用链上接口
                $params =  [
                    'currency_name' => $withdraw->currencyCoin->name,
                    'chain_address' => $withdraw->address,
                    'real_number' => $withdraw->real_number,
                    'total_account' => $withdraw->currencyCoin->total_account,
                    'key' => $withdraw->currencyCoin->origin_key,
                ];
                $query_str = md5(http_build_query($params));
//                if (Cache::has($query_str)) {
//                    throw new \Exception('请勿重复操作,以免给您带来损失,建议用区块链浏览器查询该提币地址的交易记录');
//                }
//                Cache::put($query_str, 1, Carbon::now()->addMinutes(5));
                $result = GoChainDAO::auditUserWithdraw($withdraw);
                if (!isset($result['code']) || $result['code'] != 0) {
                    throw new \Exception('审核失败,' . $result['errorinfo']);
                }
                $withdraw->txid = $result['data']['txid'] ?? ''; //交易哈希信息
                $withdraw->save();
            }
        } else {
            // 提币未通过
        }
    }
}
