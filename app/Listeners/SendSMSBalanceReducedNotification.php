<?php

namespace App\Listeners;

use App\Events\BalanceReduced;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Includes\apifunction;
use App\Models\AdminSettings;


class SendSMSBalanceReducedNotification
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
     * @param  BalanceReduced  $event
     * @return void
     */
    public function handle(BalanceReduced $event)
    {
        $this->smsProvider->sendSms($event->user->getPhoneNumber(), $this->getSMSFormat($event->currentBalance, $event->prevBalance));
    }

    public function getSMSFormat($currentBalance, $prevBalance)
    {
        return env('APP_URL').': Saldo anda telah terpakai '. $this->settings->currency_symbol. ' ' .number_format($prevBalance - $currentBalance) .'. Saldo anda sekarang '.$this->settings->currency_symbol. ' ' .number_format($currentBalance);
    }
}
