<?php

namespace App\Events\Gateway;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WorkerStopEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $businessWorker;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($business_worker)
    {
        $this->businessWorker = $business_worker;
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
