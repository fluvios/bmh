<?php

namespace App\Listeners;

use App\Events\NewBankTopupTransfer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Includes\apifunction;
use App\Models\AdminSettings;

class SendSMSBankTopupNotification
{
    /**
     * SMS Provider
     */
    public $smsProvider;

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
     * @param  NewBankTransfer  $event
     * @return void
     */
    public function handle(NewBankTopupTransfer $event)
    {
        $this->smsProvider->sendsms($event->user->getPhoneNumber(), $this->getSMSFormat($this->settings->currency_symbol. ' ' .number_format($event->deposit->amount), $event->bank, $event->deposit->getExpiry()));
    }

    public function getSMSFormat($amount, $bank, $expiry)
    {
        return env('APP_URL').': Segera transfer TEPAT ' . $amount . ' ke rek '. $bank->name . ' ' . $bank->account_number . ' an. '.$bank->account_name . ' untuk Topup sebelum '. $expiry .' WIB';
    }
}
