<?php

namespace App\Policies;

use App\Models\Approver;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ApproverPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('administrator') || $user->hasPermissionTo('view approver');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Approver $approver): bool
    {
        return $user->hasRole('administrator') || $user->hasPermissionTo('view approver');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('administrator') || $user->hasPermissionTo('add approver');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Approver $approver): bool
    {
        return $user->hasRole('administrator') || $user->hasPermissionTo('edit approver');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Approver $approver): bool
    {
        return $user->hasRole('administrator') || $user->hasPermissionTo('delete approver');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Approver $approver): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Approver $approver): bool
    {
        //
    }
}
