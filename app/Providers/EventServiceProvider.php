<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
        'App\Events\NewBankTransfer' => [
            'App\Listeners\SendSMSBankNotification',
            'App\Listeners\SendEmailBankNotification',
            'App\Listeners\SendPusherBankNotification',
        ],
        'App\Events\NewBankTopupTransfer' => [
            'App\Listeners\SendSMSBankTopupNotification',
            'App\Listeners\SendEmailBankTopupNotification',
            'App\Listeners\SendPusherBankTopupNotification',
        ],
        'App\Events\TopupSuccess' => [
            'App\Listeners\SendSMSTopupNotification',
            'App\Listeners\SendEmailTopupNotification',
            'App\Listeners\SendPusherTopupNotification',
        ],
        'App\Events\BalanceReduced' => [
            'App\Listeners\SendSMSBalanceReducedNotification',
            'App\Listeners\SendEmailBalanceReducedNotification',
            'App\Listeners\SendPusherBalanceReducedNotification',
        ],
        'App\Events\DonationSuccess' => [
            'App\Listeners\SendSMSDonationSuccessNotification',
            'App\Listeners\SendEmailDonationSuccessNotification',
            'App\Listeners\SendPusherDonationSuccessNotification',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
