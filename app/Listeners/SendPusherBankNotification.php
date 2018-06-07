<?php

namespace App\Listeners;

use App\Events\NewBankTransfer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Pusher\Laravel\PusherManager;
use App\Models\AdminSettings;

class SendPusherBankNotification
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
     * @param  NewBankTransfer  $event
     * @return void
     */
    public function handle(NewBankTransfer $event)
    {
        $this->pusher->trigger('berbagikebaikan', 'bank-transfer', ['message' => $this->getSMSFormat($this->settings->currency_symbol. ' ' .number_format($event->donation->donation), $event->bank, $event->donation->campaigns_id, $event->donation->getExpiry())]);
    }

    public function getSMSFormat($amount, $bank, $campaignId, $expiry)
    {
        return env('APP_URL').': Silakan melakukan Transfer Sebesar ' . $amount . ' Ke Rekening : '. $bank->name . ' ' . $bank->account_number . ' Atas Nama :  '.$bank->account_name . ' sebelum '. $expiry .' WIB';
    }
}
