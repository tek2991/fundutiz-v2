<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Transaction;
use App\Models\FinancialYear;
use Illuminate\Auth\Access\Response;

class TransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('administrator') || $user->hasPermissionTo('view transaction');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        return $user->hasRole('administrator') || ($user->hasPermissionTo('view transaction') && $transaction->office_id === $user->office_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('administrator') || $user->hasPermissionTo('add transaction');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Transaction $transaction): bool
    {
        $current_fy_id = FinancialYear::where('is_active', 1)->first()->id;

        return $user->hasRole('administrator') ||
            ($user->hasPermissionTo('edit transaction') &&
                $transaction->office_id === $user->office_id &&
                $transaction->financial_year_id === $current_fy_id &&
                $transaction->created_by === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Transaction $transaction): bool
    {
        $current_fy_id = FinancialYear::where('is_active', 1)->first()->id;

        return $user->hasRole('administrator') ||
            ($user->hasPermissionTo('delete transaction') &&
                $transaction->office_id === $user->office_id &&
                $transaction->financial_year_id === $current_fy_id &&
                $transaction->created_by === $user->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Transaction $transaction): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Transaction $transaction): bool
    {
        //
    }
}
