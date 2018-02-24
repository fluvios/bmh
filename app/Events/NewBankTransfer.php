<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\Banks;
use App\Models\Donations;
use App\Models\User;

class NewBankTransfer
{
    use InteractsWithSockets, SerializesModels;

    public $donation; 

    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Donations $donation, User $user, Banks $bank)
    {
        $this->donation = $donation;
        $this->user     = $user;
        $this->bank     = $bank;
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
