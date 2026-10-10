<?php

return [
    'internal_content_token' => env('INTERNAL_CONTENT_TOKEN'),
    'newsletter_url' => env('SUTHARTECH_NEWSLETTER_URL', 'http://admin.localhost:8001/api/newsletter/subscribe'),
    'forms_notification_url' => env('SUTHARTECH_FORMS_NOTIFICATION_URL', 'http://admin.localhost:8001/api/internal/forms/contact'),
    'form_protection_settings_url' => env('SUTHARTECH_FORM_PROTECTION_SETTINGS_URL', 'http://admin.localhost:8001/api/internal/forms/protection'),

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

];
