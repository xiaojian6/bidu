<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\Gateway\GatewayEventsSubscriber;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\LeverSubmitOrderEvent::class => [
        ],
        \App\Events\RechargeEvent::class => [
        ],
        \App\Events\RealNameEvent::class => [
        ],
        \App\Events\UserRegisterEvent::class => [
            \App\Listeners\UserRegisterListener::class,
        ],
        \App\Events\WithdrawSubmitEvent::class => [
            \App\Listeners\WithdrawSubmitListener::class,
        ],
        \App\Events\WithdrawAuditEvent::class => [
            \App\Listeners\WithdrawAuditListener::class,
        ],
        \App\Events\LeverTradeClosedEvent::class => [
            \App\Listeners\LeverTradeClosedListener::class,
        ],
        \App\Events\BlockChainTxEvent::class => [
            \App\Listeners\BlockChainTxListener::class,
        ],
    ];

    protected $subscribe = [
        GatewayEventsSubscriber::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        //
    }
}
