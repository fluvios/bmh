<?php

namespace App\Listeners;

use App\Events\NewBankTransfer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Includes\apifunction;

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
    public function __construct(apifunction $smsProvider)
    {
        $this->smsProvider = $smsProvider;
    }

    /**
     * Handle the event.
     *
     * @param  NewBankTransfer  $event
     * @return void
     */
    public function handle(NewBankTransfer $event)
    {
        $this->smsProvider->sendsms($event->user->getPhoneNumber(), $this->getSMSFormat($event->donation->donation, $event->bank, $event->donation->campaigns_id, $event->donation->getExpiry()));
    }

    public function getSMSFormat($amount, $bank, $campaignId, $expiry)
    {
        return 'KITABISA.COM: Segera transfer TEPAT Rp. ' . $amount . ' ke rek '. $bank->name . ' ' . $bank->account_number . ' an. '.$bank->account_name . ' untuk donasi #'. $campaignId. ' sebelum '. $expiry .' WIB';
    }
}