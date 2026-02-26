<?php

return [
    'orange_sms' => [
        'base_url' => env('ORANGE_SMS_BASE_URL', 'https://api.orange.com'),
        'client_id' => env('ORANGE_SMS_CLIENT_ID'),
        'client_secret' => env('ORANGE_SMS_CLIENT_SECRET'),
        'sender' => env('ORANGE_SMS_SENDER', 'CODELEARN'),
    ],
    'deepseek' => [
        'key' => env('DEEPSEEK_API_KEY'),
        'url' => env('DEEPSEEK_BASE_URL'),
    ],
];
