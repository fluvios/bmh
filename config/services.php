<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'facebook' => [
        'client_id' => '1030581603764130',
        'client_secret' => '6bfdffc28bd657bb3496ae1fd66d354f',
        'redirect' => 'http://berbagikebaikan.org/callback/facebook',
    ],
    'google' => [ 
        'client_id' => '68089184379-gp9s4krpk747i4iin7boeu1s8uu77gle.apps.googleusercontent.com',
        'client_secret' => 'YA4bJH-KOg4GkQN4nmIo5PIz',
        'redirect' => 'http://berbagikebaikan.org/callback/google' 
    ],
];
