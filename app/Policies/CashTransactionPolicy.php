<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CashTransaction;

class CashTransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-cash-transactions');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CashTransaction $cashTransaction): bool
    {
        return $user->hasPermissionTo('view-cash-transactions');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-cash-transactions');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CashTransaction $cashTransaction): bool
    {
        return $user->hasPermissionTo('edit-cash-transactions');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CashTransaction $cashTransaction): bool
    {
        return $user->hasPermissionTo('delete-cash-transactions');
    }

}
