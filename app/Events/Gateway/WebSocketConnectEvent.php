<?php

namespace App\Events\Gateway;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WebSocketConnectEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $clientID;

    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($client_id, $data)
    {
        $this->clientID = $client_id;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
