<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Policies\UserPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\CustomerPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Order::class => OrderPolicy::class,
        Product::class => ProductPolicy::class,
        Customer::class => CustomerPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
