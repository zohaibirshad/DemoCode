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
    'facebook' => [ //change it to any provider
        'client_id' => '',
        'client_secret' => '',
        'redirect' => '',
    ],

    'google' => [ //change it to any provider
        'client_id' => '',
        'client_secret' => '',
        'redirect' => '',
    ],
    'amazonpayment' => [
        'sandbox_mode' => true,
        'store_name' => 'ACME Inc',
        'statement_name' => 'AcmeInc 555-555-5555',
        'client_id' => 'amzn1.application-oa2-client.a0acbc9ba07d44de8db9135729b6a0e1',
        'seller_id' => 'A17SQ2AWT9FGH9',
        'access_key' => 'AKIAJ6NFXIX4SQ6ABHZQ',
        'secret_key' => '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',
    ]
];
