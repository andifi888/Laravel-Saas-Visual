<?php

return [
    'paths' => [
        'allowed_origins' => [env('CORS_ALLOWED_ORIGINS', '*')],
        'allowed_origins_patterns' => [env('CORS_ALLOWED_ORIGINS_PATTER', '')],
        'allowed_headers' => [env('CORS_ALLOWED_HEADERS', '*')],
        'allowed_methods' => [env('CORS_ALLOWED_METHODS', '*')],
        'exposed_headers' => [env('CORS_EXPOSED_HEADERS', '')],
        'max_age' => env('CORS_MAX_AGE', 0),
        'supports_credentials' => env('CORS_SUPPORTS_CREDENTIALS', false),
    ],
];
