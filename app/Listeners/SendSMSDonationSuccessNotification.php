<?php

namespace App\Listeners;

use App\Events\DonationSuccess;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Includes\apifunction;
use App\Models\AdminSettings;

class SendSMSDonationSuccessNotification
{
    /**
     * SMS Provider
     */
    public $smsProvider;

    public $settings;

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
     * @param  DonationSuccess  $event
     * @return void
     */
    public function handle(DonationSuccess $event)
    {
        $this->smsProvider->sendSms($event->donation->user->getPhoneNumber(), $this->getSMSFormat());
    }

    public function getSMSFormat()
    {
        return 'Terima kasih telah berdonasi di '.env('APP_URL');
    }
}
