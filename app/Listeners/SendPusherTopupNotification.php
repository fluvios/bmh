<?php

namespace App\Listeners;

use App\Events\TopupSuccess;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPusherTopupNotification
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
     * @param  DonationSuccess  $event
     * @return void
     */
    public function handle(DonationSuccess $event)
    {
        $this->pusher->trigger('berbagikebaikan', 'topup-success', ['message' => $this->getSMSFormat($this->settings->currency_symbol. ' ' .number_format($event->depositLog->amount), $this->settings->currency_symbol. ' ' .number_format($event->user->saldo))]);
    }

    public function getSMSFormat($amount, $balance)
    {
        return env('APP_URL').': Topup saldo anda sebesar '. $amount .' berhasil. Saldo anda sekarang '.$balance;
    }
}
