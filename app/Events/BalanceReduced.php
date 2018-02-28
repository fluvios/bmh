<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\User;

class BalanceReduced
{
    use InteractsWithSockets, SerializesModels;

    /**
     * User model
     * @var App\Models\User
     */
    public $user;

    /**
     * Current balance after reduced
     * @var [type]
     */
    public $currentBalance;

    /**
     * Previous balance before used
     * @var [type]
     */
    public $prevBalance;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $currentBalance, $prevBalance)
    {
        $this->user = $user;
        $this->currentBalance = $currentBalance;
        $this->prevBalance = $prevBalance;
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
