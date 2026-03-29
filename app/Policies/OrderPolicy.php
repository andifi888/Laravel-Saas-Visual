<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_dashboard');
    }

    public function view(User $user, Order $order): bool
    {
        return $user->can('view_dashboard');
    }

    public function create(User $user): bool
    {
        return $user->can('manage_orders');
    }

    public function update(User $user, Order $order): bool
    {
        return $user->can('manage_orders');
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->can('manage_orders') && in_array($order->status, ['pending', 'cancelled']);
    }
}
