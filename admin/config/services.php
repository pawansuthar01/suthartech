<?php

return [
    'suthartech_content_url' => env('SUTHARTECH_CONTENT_URL', 'http://127.0.0.1:8003/api/internal/content'),
    'suthartech_content_token' => env('SUTHARTECH_CONTENT_TOKEN'),
    'suthartech_admin_url' => env('SUTHARTECH_ADMIN_URL', env('APP_URL', 'http://admin.localhost:8001')),
    'suthartech_public_url' => env('SUTHARTECH_PUBLIC_URL', 'http://localhost:8001'),

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

    'msg91' => [
        'authkey'            => env('MSG91_AUTHKEY', ''),
        'integrated_number'  => env('MSG91_INTEGRATED_NUMBER', '918619218048'),
    ],

    'razorpay' => [
        'key_id'     => env('RAZORPAY_KEY_ID', ''),
        'key_secret' => env('RAZORPAY_KEY_SECRET', ''),
    ],

];
