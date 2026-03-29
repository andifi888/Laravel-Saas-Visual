<?php

return [
    'models' => [
        \App\Models\User::class,
        \App\Models\Order::class,
        \App\Models\Product::class,
        \App\Models\Customer::class,
    ],
    'exclude' => [],
    'log_unguarded' => true,
    'log_old_attributes' => true,
    'log_raw' => false,
    'subject_allowed' => true,
    'default_log_name' => 'default',
];
