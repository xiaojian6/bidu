<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\UserRegisterEvent;
use App\DAO\GoChainDAO;
use App\Models\UsersWallet;

class UserRegisterListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(UserRegisterEvent $event)
    {
        $user = $event->user;
        $user->refresh();
        // 生成钱包
        UsersWallet::makeWallet($user->id);
        // 同步用户
        $result = GoChainDAO::syncUserInfo($user);
        if (!isset($result['code']) || $result['code'] != 0) {
            throw new \Exception('同步信息失败:' . $result['errorinfo']);
        }
    }
}
