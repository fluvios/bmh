<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Includes\apifunction;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(apifunction::class, function ($app) {
            return new apifunction();
        });
    }
}
