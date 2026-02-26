<?php

return [

    'gmail' => [
        'host' => env('GMAIL_SMTP_HOST', 'smtp.gmail.com'),
        'port' => env('GMAIL_SMTP_PORT', 587),
        'username' => env('GMAIL_SMTP_USERNAME'),
        'password' => env('GMAIL_SMTP_PASSWORD'),
        'encryption' => env('GMAIL_SMTP_ENCRYPTION', 'tls'),
        'from_address' => env('GMAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')),
        'from_name' => env('GMAIL_FROM_NAME', env('MAIL_FROM_NAME')),
    ],
    'orange_sms' => [
        'base_url' => env('ORANGE_SMS_BASE_URL', 'https://api.orange.com'),
        'client_id' => env('ORANGE_SMS_CLIENT_ID'),
        'client_secret' => env('ORANGE_SMS_CLIENT_SECRET'),
        'sender' => env('ORANGE_SMS_SENDER', 'CODELEARN'),
    ],
];
