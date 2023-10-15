<?php

namespace App\Policies;

use App\CurationGroup;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CurationGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any curation groups.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list curation-groups');
    }

    /**
     * Determine whether the user can view the curation group.
     */
    public function view(User $user, CurationGroup $curationGroup): bool
    {
        return $user->hasPermissionTo('list curation-groups');
    }

    /**
     * Determine whether the user can create curation groups.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create curation-groups');
    }

    /**
     * Determine whether the user can update the curation group.
     */
    public function update(User $user, CurationGroup $curationGroup): bool
    {
        return $user->hasPermissionTo('update curation-groups');
    }

    /**
     * Determine whether the user can delete the curation group.
     */
    public function delete(User $user, CurationGroup $curationGroup): bool
    {
        return $user->hasPermissionTo('delete curation-groups');
    }

    /**
     * Determine whether the user can restore the curation group.
     */
    public function restore(User $user, CurationGroup $curationGroup): bool
    {
        return $user->hasPermissionTo('delete curation-groups');
    }

    /**
     * Determine whether the user can permanently delete the curation group.
     */
    public function forceDelete(User $user, CurationGroup $curationGroup): bool
    {
        return $user->hasPermissionTo('delete curation-groups');
    }
}
