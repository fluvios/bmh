<?php

namespace App\Listeners;

use App\Events\BalanceReduced;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Pusher\Laravel\PusherManager;
use App\Models\AdminSettings;

class SendPusherBalanceReducedNotification
{
    protected $pusher;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PusherManager $pusher, AdminSettings $settings)
    {
        $this->pusher = $pusher;
        $this->settings = $settings::first();
    }

    /**
     * Handle the event.
     *
     * @param  BalanceReduced  $event
     * @return void
     */
    public function handle(BalanceReduced $event)
    {
        $this->pusher->trigger('berbagikebaikan', 'balance-reduced', ['message' => $this->getSMSFormat($event->currentBalance, $event->prevBalance)]);
    }

    public function getSMSFormat($currentBalance, $prevBalance)
    {
        return env('APP_URL').': Saldo anda telah terpakai '. $this->settings->currency_symbol. ' ' .number_format($prevBalance - $currentBalance) .'. Saldo anda sekarang '.$this->settings->currency_symbol. ' ' .number_format($currentBalance);
    }
}
