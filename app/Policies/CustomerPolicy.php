<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_dashboard');
    }

    public function view(User $user, Customer $customer): bool
    {
        return $user->can('view_dashboard');
    }

    public function create(User $user): bool
    {
        return $user->can('manage_customers');
    }

    public function update(User $user, Customer $customer): bool
    {
        return $user->can('manage_customers');
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->can('manage_customers');
    }
}
