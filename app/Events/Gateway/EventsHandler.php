<?php

namespace App\Events\Gateway;

class EventsHandler
{
    public static function onWorkerStart($business_worker)
    {
        event(new WorkerStartEvent($business_worker));
    }

    public static function onConnect($client_id)
    {
        event(new ConnectEvent($client_id));
    }

    public static function onWebSocketConnect($client_id, $data)
    {
        event(new WebSocketConnectEvent($client_id, $data));
    }

    public static function onMessage($client_id, $message)
    {
        event(new MessageEvent($client_id, $message));
    }

    public static function onClose($client_id)
    {
        event(new CloseEvent($client_id));
    }

    public static function onWorkerStop($business_worker)
    {
        event(new WorkerStopEvent($business_worker));
    }
}