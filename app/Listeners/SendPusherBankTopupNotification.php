<?php

namespace App\Listeners;

use App\Events\NewBankTopupTransfer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Pusher\Laravel\PusherManager;
use App\Models\AdminSettings;

class SendPusherBankTopupNotification
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
     * @param  NewBankTopupTransfer  $event
     * @return void
     */
    public function handle(NewBankTopupTransfer $event)
    {
        $this->pusher->trigger('berbagikebaikan', 'bank-topup-transfer', ['message' => $this->getSMSFormat($this->settings->currency_symbol. ' ' .number_format($event->deposit->amount), $event->bank, $event->deposit->getExpiry())]);
    }

    public function getSMSFormat($amount, $bank, $expiry)
    {
        return env('APP_URL').': Silakan melakukan Transfer Sebesar ' . $amount . ' ke Rekening: '. $bank->name . ' ' . $bank->account_number . ' Atas nama: '.$bank->account_name . ' untuk Topup Saldo sebelum '. $expiry .' WIB';
    }
}
