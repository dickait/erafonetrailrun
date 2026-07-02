<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Third Party Services
     |--------------------------------------------------------------------------
     |
     | This file is for storing the credentials for third party services such
     | as Mailgun, Postmark, AWS and more. This file provides the de facto
     | location for this type of information, allowing packages to have
     | a conventional file to locate the various service credentials.
     |
     */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'mayar' => [
        'api_key' => env('MAYAR_API_KEY'),
        'api_url' => env('MAYAR_API_URL', 'https://api.mayar.club/hl/v1'),
        'webhook_secret' => env('MAYAR_WEBHOOK_SECRET'),
    ],

    'payment' => env('PAYMENT', 'manual'),

    'is_open' => filter_var(env('IS_OPEN', true), FILTER_VALIDATE_BOOLEAN),

    'is_over' => filter_var(env('IS_OVER', false), FILTER_VALIDATE_BOOLEAN),

    'midtrans' => [
        'server_key' => env('MIDTRANS_API_SERVER_KEY'),
        'client_key' => env('MIDTRANS_API_CLIENT_KEY'),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    ],

    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    ],

];
