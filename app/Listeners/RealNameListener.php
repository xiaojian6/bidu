<?php

namespace App\Listeners;

use App\Events\RealNameEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\DAO\UserDAO;

class RealNameListener
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
     * @param  RealNameEvent  $event
     * @return void
     */
    public function handle(RealNameEvent $event)
    {
        $user = $event->getUser();
        UserDAO::checkUpgradeAtelierCondition($user);
    }
}
