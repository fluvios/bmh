<?php

namespace App\Listeners;

use App\Events\TopupSuccess;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

use App\Models\AdminSettings;

class SendEmailTopupNotification
{
    public $settings;

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
     * @param  TopupSuccess  $event
     * @return void
     */
    public function handle(TopupSuccess $event)
    {
        $data = $this->getSMSFormat($this->settings->currency_symbol. ' ' .number_format($event->depositLog->amount), $this->settings->currency_symbol. ' ' .number_format($event->user->saldo));
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
            $message->subject('Topup Success');
            $message->to($event->depositLog->email, $event->user->name);
          }
        );
    }

    public function getSMSFormat($amount, $balance)
    {
        return env('APP_URL').': Topup sebesar '. $amount .' berhasil. Saldo anda sekarang '.$balance;
    }
}
