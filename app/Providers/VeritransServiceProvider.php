<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Includes\Veritrans\Veritrans_Config;

class VeritransServiceProvider extends ServiceProvider
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
        Veritrans_Config::$serverKey = env('MIDTRANS_SERVER_KEY', '');
        //set Veritrans_Config::$isProduction  value to true for production mode
        Veritrans_Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', true);
        Veritrans_Config::$is3ds = env('MIDTRANS_3DS', true);
    }
}
