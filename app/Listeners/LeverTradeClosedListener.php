<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\LeverTradeClosedEvent;
use App\Models\LeverTransaction;

class LeverTradeClosedListener
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
    public function handle(LeverTradeClosedEvent $event)
    {
        $lever_trades = $event->leverTrades;
        LeverTransaction::pushDeletedTrade($lever_trades);
    }
}
