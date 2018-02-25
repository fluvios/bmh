<?php

namespace App\Listeners;

use App\Events\NewBankTransfer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Models\Campaigns;
use App\Models\AdminSettings;

class SendEmailBankNotification
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
     * @param  NewBankTransfer  $event
     * @return void
     */
    public function handle(NewBankTransfer $event)
    {
        $title_site    = $this->settings->title;
        $_email_noreply = $this->settings->email_no_reply;
        $amount         = $this->settings->currency_symbol. ' ' .number_format($event->donation->donation);
        $campaign       = Campaigns::find($event->donation->campaigns_id);
        $bank           = $event->bank;
        $expiry         = $event->donation->getExpiry();
        Mail::send(
          'emails.transfer-reminder',
          compact('amount', 'campaign', 'bank', 'expiry', 'title_site'),
          function ($message) use (
            $event,
            $title_site,
            $_email_noreply
          ) {
            $message->from($_email_noreply, $title_site);
            $message->subject('Transfer Reminder');
            $message->to($event->donation->email, $event->user->name);
          }
        );
    }
}
