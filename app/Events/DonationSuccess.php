<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\User;
use App\Models\Donations;


class DonationSuccess
{
    use InteractsWithSockets, SerializesModels;

    public $user;

    public $donation;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Donations $donation, User $user)
    {
        $this->donation = $donation;
        $this->user     = $user;
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
