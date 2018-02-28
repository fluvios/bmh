<?php

namespace App\Listeners;

use App\Events\DonationSuccess;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AdminSettings;
use Mail;

class SendEmailDonationSuccessNotification
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
     * @param  DonationSuccess  $event
     * @return void
     */
    public function handle(DonationSuccess $event)
    {
        $data = $this->getSMSFormat();
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
            $message->subject('Donasi Berhasil');
            $message->to($event->donation->email, $event->donation->fullname);
          }
        );
    }

    public function getSMSFormat()
    {
        return 'Terima kasih telah berdonasi di '.env('APP_URL');
    }
}
