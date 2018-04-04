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
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'twitter' => [
        'client_id' => 'R9Fw0JbGzv7fZgbIsjJgxvEwK',
        'client_secret' => 'GC67GTpzDSy5RD6fpDOfcPQe0KAXJas9xlnmzVcxHMIfX6TXua',
        'redirect' => env('TWITTER_THIRD'),
    ],
    'facebook' => [
        'client_id' => '330343927370751',
        'client_secret' => '9cf55eef5e1a550ff8c6067aee5eec4f',
        'redirect' => env('FACEBOOK_THIRD'),
    ],
    //google 回调地址在app配置内写死
    'google' => [
        'client_id' => '707985123880-nmln29q0q11jai8oi37c7kpsah9eqcn4.apps.googleusercontent.com',
        'client_secret' => 'FLLeUn9yK1gm-FaY74v8lOL3',
        'redirect' => env('GOOGLE_THIRD'),

    ],

];
