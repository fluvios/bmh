<?php

namespace App\Listeners;

use App\Events\TopupSuccess;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Includes\apifunction;
use App\Models\AdminSettings;


class SendSMSTopupNotification
{
    /**
     * SMS Provider
     */
    public $smsProvider;

    public $settings;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(apifunction $smsProvider, AdminSettings $settings)
    {
        $this->smsProvider = $smsProvider;
        $this->settings = $settings::first();
    }

    /**
     * Handle the event.
     *
     * @param  TopupSuccess  $event
     * @return void
     */
    public function handle(TopupSuccess $event)
    {
        $this->smsProvider->sendsms($event->user->getPhoneNumber(), $this->getSMSFormat($this->settings->currency_symbol. ' ' .number_format($event->depositLog->amount), $this->settings->currency_symbol. ' ' .number_format($event->user->saldo)));
    }

    public function getSMSFormat($amount, $balance)
    {
        return env('APP_URL').': Topup saldo anda sebesar '. $amount .' berhasil. Saldo anda sekarang '.$balance;
    }
}
