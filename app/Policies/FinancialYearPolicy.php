<?php

namespace App\Policies;

use App\Models\FinancialYear;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FinancialYearPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('administrator') || $user->hasPermissionTo('view financial year');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FinancialYear $financialYear): bool
    {
        return $user->hasRole('administrator') || $user->hasPermissionTo('view financial year');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('administrator') || $user->hasPermissionTo('add financial year');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FinancialYear $financialYear): bool
    {
        return ($user->hasRole('administrator') || $user->hasPermissionTo('edit financial year')) && ! $financialYear->isActive();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FinancialYear $financialYear): bool
    {
        return ($user->hasRole('administrator') || $user->hasPermissionTo('delete financial year')) && ! $financialYear->isActive();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FinancialYear $financialYear): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FinancialYear $financialYear): bool
    {
        //
    }
}
