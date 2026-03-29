<?php

return [
    'default' => env('SESSION_DRIVER', 'file'),
    'store' => env('SESSION_STORE'),
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),
    'encrypt' => env('SESSION_ENCRYPT', false),
    'files' => storage_path('framework/sessions'),
    'connection' => env('SESSION_CONNECTION'),
    'table' => env('SESSION_TABLE', 'sessions'),
    'broker' => env('SESSION_BROKER', null),
    'same_site' => env('SESSION_SAME_SITE', 'lax'),
    'secure' => env('SESSION_SECURE_COOKIE'),
    'http_only' => env('SESSION_HTTP_ONLY', true),
    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),
];
