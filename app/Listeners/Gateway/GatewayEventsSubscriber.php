<?php

namespace App\Listeners\Gateway;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use GatewayWorker\Lib\Gateway;
use App\Events;
use App\Logic\SocketLogic;

class GatewayEventsSubscriber
{
    protected $connection;

    protected $workerId;

    protected $subscribed = [];

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
    public function handle($event)
    {
        //
    }

    public function subscribe($events)
    {
        $events->listen(
            Events\Gateway\WorkerStartEvent::class,
            self::class . '@onWorkerStart'
        );

        $events->listen(
            Events\Gageway\WorkerStopEvent::class,
            self::class . '@onWorkerStop'
        );

        $events->listen(
            Events\Gateway\WebSocketConnectEvent::class,
            self::class . '@onWebSocketConnect'
        );

        $events->listen(
            Events\Gateway\ConnectEvent::class,
            self::class . '@onConnect'
        );

        $events->listen(
            Events\Gateway\MessageEvent::class,
            self::class . '@onMessage'
        );

        $events->listen(
            Events\Gateway\CloseEvent::class,
            self::class . '@onClose'
        );
    }

    public function onWorkerStart($event)
    {
    }

    public function onWorkerStop($event)
    {
    }

    public function onWebSocketConnect($event)
    {
    }

    public function onConnect($event)
    {
        //Gateway::sendToAll('client_id:' . $event->clientId . ' login');
    }

    public function onMessage($event)
    {
        SocketLogic::messageHandler($event);
    }

    public function onClose($event)
    {
    }
}
