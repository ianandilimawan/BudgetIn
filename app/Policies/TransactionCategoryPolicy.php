<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TransactionCategory;

class TransactionCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-transaction-categories');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TransactionCategory $transactionCategory): bool
    {
        return $user->hasPermissionTo('view-transaction-categories');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-transaction-categories');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TransactionCategory $transactionCategory): bool
    {
        return $user->hasPermissionTo('edit-transaction-categories');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TransactionCategory $transactionCategory): bool
    {
        return $user->hasPermissionTo('delete-transaction-categories');
    }

}
