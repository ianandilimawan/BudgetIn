<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CashAccount;

class CashAccountPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-cash-accounts');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CashAccount $cashAccount): bool
    {
        return $user->hasPermissionTo('view-cash-accounts');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-cash-accounts');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CashAccount $cashAccount): bool
    {
        return $user->hasPermissionTo('edit-cash-accounts');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CashAccount $cashAccount): bool
    {
        return $user->hasPermissionTo('delete-cash-accounts');
    }

}
