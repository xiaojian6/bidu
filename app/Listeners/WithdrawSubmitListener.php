<?php

namespace App\Listeners;

use App\Events\WithdrawSubmitEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\DAO\GoChainDAO;
use App\Models\UsersWallet;

class WithdrawSubmitListener
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
     * @param  WithdrawSubmitEvent  $event
     * @return void
     */
    public function handle(WithdrawSubmitEvent $event)
    {
        $withdraw = $event->withdraw;
        $withdraw->refresh();
        if ($withdraw->status != 1 || bc_comp($withdraw->real_number, '0') <= 0) {
            throw new \Exception('提币信息状态异常');
        }
        $result = GoChainDAO::submitUserWithdraw($withdraw);
        if (!isset($result['code']) || $result['code'] != 0) {
            throw new \Exception('同步信息失败,' . $result['errorinfo']);
        }
    }
}
