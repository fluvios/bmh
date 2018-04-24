<?php

namespace App\Listeners;

use App\Events\NewBankTransfer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Includes\apifunction;
use App\Models\AdminSettings;

class SendSMSBankNotification
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
    public function handle(NewBankTransfer $event)
    {
        $this->smsProvider->sendsms($event->user->getPhoneNumber(), $this->getSMSFormat($this->settings->currency_symbol. ' ' .number_format($event->donation->donation), $event->bank, $event->donation->campaigns_id, $event->donation->getExpiry()));
    }

    public function getSMSFormat($amount, $bank, $campaignId, $expiry)
    {
        return env('APP_URL').': Silakan melakukan Transfer Sebesar ' . $amount . ' Ke Rekening : '. $bank->name . ' ' . $bank->account_number . ' Atas Nama :  '.$bank->account_name . ' sebelum '. $expiry .' WIB';
    }
}
