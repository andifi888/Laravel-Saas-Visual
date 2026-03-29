<?php

namespace App\Helpers;

use App\Models\Tenant;

if (!function_exists('tenant')) {
    function tenant(): ?Tenant
    {
        return app('tenant');
    }
}

if (!function_exists('user')) {
    function user()
    {
        return auth()->user();
    }
}

if (!function_exists('format_currency')) {
    function format_currency(float $amount): string
    {
        return '$' . number_format($amount, 2);
    }
}

if (!function_exists('format_number')) {
    function format_number(int|float $num): string
    {
        return number_format($num);
    }
}

if (!function_exists('format_percentage')) {
    function format_percentage(float $value): string
    {
        return number_format($value, 1) . '%';
    }
}

if (!function_exists('active_link')) {
    function active_link(string $route, string $class = 'active'): string
    {
        return request()->routeIs($route) ? $class : '';
    }
}
