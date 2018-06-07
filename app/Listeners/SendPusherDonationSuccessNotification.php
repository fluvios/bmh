<?php

namespace App\Listeners;

use App\Events\DonationSuccess;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Pusher\Laravel\PusherManager;
use App\Models\AdminSettings;

class SendPusherDonationSuccessNotification
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
     * @param  DonationSuccess  $event
     * @return void
     */
    public function handle(DonationSuccess $event)
    {
        $this->pusher->trigger('berbagikebaikan', 'donation-success', ['message' => $this->getSMSFormat()]);
    }

    public function getSMSFormat()
    {
        return 'Terima kasih donasi anda sudah Kami Terima Semoga Dapat bermanfaat buat kita semua'.env('APP_URL');
    }
}
