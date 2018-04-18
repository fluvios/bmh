<?php

namespace App\Listeners;

use App\Events\NewBankTopupTransfer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AdminSettings;
use Mail;

class SendEmailBankTopupNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(AdminSettings $settings)
    {
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
        $data = $this->getSMSFormat($this->settings->currency_symbol. ' ' .number_format($event->deposit->amount), $event->bank, $event->deposit->getExpiry());
        $title_site    = $this->settings->title;
        $_email_noreply = $this->settings->email_no_reply;
        Mail::send(
          'emails.plain-text',
          compact('data', 'title_site'),
          function ($message) use (
            $event,
            $title_site,
            $_email_noreply
          ) {
            $message->from($_email_noreply, $title_site);
            $message->subject('PEMBAYARAN TOPUP SALDO');
            $message->to($event->deposit->email, $event->deposit->fullname);
          }
        );
    }

    public function getSMSFormat($amount, $bank, $expiry)
    {
        return env('APP_URL').': Terima kasih telah melakukan TopUp saldo Sebesar ' . $amount . ' Transfer nominal tersebut ke rek '. $bank->name . ' ' . $bank->account_number . ' an. '.$bank->account_name . ' Pastikan anda transfer sebelum '. $expiry .' WIB atau TopUp anda otomatis dibatalkan oleh sistem';
    }
}
