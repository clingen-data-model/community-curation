<?php

namespace App\Policies;

use App\User;
use App\WorkingGroup;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkingGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any working groups.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list working-groups');
    }

    /**
     * Determine whether the user can view the working group.
     */
    public function view(User $user, WorkingGroup $workingGroup): bool
    {
        return $user->hasPermissionTo('list working-groups');
    }

    /**
     * Determine whether the user can create working groups.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create working-groups');
    }

    /**
     * Determine whether the user can update the working group.
     */
    public function update(User $user, WorkingGroup $workingGroup): bool
    {
        return $user->hasPermissionTo('update working-groups');
    }

    /**
     * Determine whether the user can delete the working group.
     */
    public function delete(User $user, WorkingGroup $workingGroup): bool
    {
        return $user->hasPermissionTo('delete working-groups');
    }

    /**
     * Determine whether the user can restore the working group.
     */
    public function restore(User $user, WorkingGroup $workingGroup): bool
    {
        return $user->hasPermissionTo('delete working-groups');
    }

    /**
     * Determine whether the user can permanently delete the working group.
     */
    public function forceDelete(User $user, WorkingGroup $workingGroup): bool
    {
        return $user->hasPermissionTo('delete working-groups');
    }
}
