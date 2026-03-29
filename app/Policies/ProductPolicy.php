<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_dashboard');
    }

    public function view(User $user, Product $product): bool
    {
        return $user->can('view_dashboard');
    }

    public function create(User $user): bool
    {
        return $user->can('manage_products');
    }

    public function update(User $user, Product $product): bool
    {
        return $user->can('manage_products');
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->can('manage_products');
    }
}
