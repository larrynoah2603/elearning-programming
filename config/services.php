<?php

return [
    'deepseek' => [
        'key' => env('DEEPSEEK_API_KEY', 'sk-caa23ab1c7c940cda498478d6c39bd77'),
        'url' => env('DEEPSEEK_BASE_URL', 'https://api.deepseek.com/v1'), // valeur par défaut ajoutée
        'model' => env('DEEPSEEK_MODEL', 'deepseek-chat'),
        'timeout' => env('DEEPSEEK_TIMEOUT', 25),
    ],

    'orange_sms' => [
        'base_url' => env('ORANGE_SMS_BASE_URL', 'https://api.orange.com'),
        'client_id' => env('ORANGE_SMS_CLIENT_ID'),
        'client_secret' => env('ORANGE_SMS_CLIENT_SECRET'),
        'sender' => env('ORANGE_SMS_SENDER', 'CODELEARN'),
    ], 

];
