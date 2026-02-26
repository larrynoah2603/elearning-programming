<?php

return [
    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
        'url' => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'),
        'model' => env('GEMINI_MODEL', 'gemini-flash-latest'),
        'timeout' => env('GEMINI_TIMEOUT', 25),
    ],

    'orange_sms' => [
        'base_url' => env('ORANGE_SMS_BASE_URL', 'https://api.orange.com'),
        'client_id' => env('ORANGE_SMS_CLIENT_ID'),
        'client_secret' => env('ORANGE_SMS_CLIENT_SECRET'),
        'sender' => env('ORANGE_SMS_SENDER', 'CODELEARN'),
    ], 

];
