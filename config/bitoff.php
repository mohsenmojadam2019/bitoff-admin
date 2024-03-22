<?php

return [
    'sync_wallet' => [
        'url' => env('GATEWAY_URL'),
        'token' => env('GATEWAY_TOKEN'),
    ],

    'bit_front' => [
        'url' => env('FRONTEND_ADDRESS'),
    ],
    'bit_api' => [
        'url' => env('API_URL'),
    ],
    'bit-storage' => [
        'url' => env('STATIC_URL'),
    ],
];
