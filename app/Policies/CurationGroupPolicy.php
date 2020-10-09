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
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list curation-groups');
    }

    /**
     * Determine whether the user can view the curation group.
     *
     * @return mixed
     */
    public function view(User $user, CurationGroup $curationGroup)
    {
        return $user->hasPermissionTo('list curation-groups');
    }

    /**
     * Determine whether the user can create curation groups.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create curation-groups');
    }

    /**
     * Determine whether the user can update the curation group.
     *
     * @return mixed
     */
    public function update(User $user, CurationGroup $curationGroup)
    {
        return $user->hasPermissionTo('update curation-groups');
    }

    /**
     * Determine whether the user can delete the curation group.
     *
     * @return mixed
     */
    public function delete(User $user, CurationGroup $curationGroup)
    {
        return $user->hasPermissionTo('delete curation-groups');
    }

    /**
     * Determine whether the user can restore the curation group.
     *
     * @return mixed
     */
    public function restore(User $user, CurationGroup $curationGroup)
    {
        return $user->hasPermissionTo('delete curation-groups');
    }

    /**
     * Determine whether the user can permanently delete the curation group.
     *
     * @return mixed
     */
    public function forceDelete(User $user, CurationGroup $curationGroup)
    {
        return $user->hasPermissionTo('delete curation-groups');
    }
}
