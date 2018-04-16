<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\Broadcast;

class BroadcastEmail
{
    use InteractsWithSockets, SerializesModels;

    public $broadcast;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Broadcast $broadcast)
    {
        //
        $this->broadcast = $broadcast;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
