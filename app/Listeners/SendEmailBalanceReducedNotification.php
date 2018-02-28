<?php

namespace App\Listeners;

use App\Events\BalanceReduced;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Mail;
use App\Models\AdminSettings;
class SendEmailBalanceReducedNotification
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
     * @param  BalanceReduced  $event
     * @return void
     */
    public function handle(BalanceReduced $event)
    {
        $data = $this->getSMSFormat($event->currentBalance, $event->prevBalance);
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
            $message->subject('Info Pengurangan Saldo');
            $message->to($event->user->email, $event->user->name);
          }
        );
    }

    public function getSMSFormat($currentBalance, $prevBalance)
    {
        return env('APP_URL').': Saldo anda telah terpakai '. $this->settings->currency_symbol. ' ' .number_format($prevBalance - $currentBalance) .'. Saldo anda sekarang '.$this->settings->currency_symbol. ' ' .number_format($currentBalance);
    }
}
