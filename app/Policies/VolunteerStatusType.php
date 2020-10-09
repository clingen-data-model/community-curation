<?php

namespace App\Policies;

use App\User;
use App\VolunteerType;
use Illuminate\Auth\Access\HandlesAuthorization;

class VolunteerStatusType
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any volunteer statuses.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        $user->hasPermissionTo('list volunteer-types');
    }

    /**
     * Determine whether the user can view the volunteer status.
     *
     * @return mixed
     */
    public function view(User $user, VolunteerType $volunteerType)
    {
        $user->hasPermissionTo('list volunteer-types');
    }

    /**
     * Determine whether the user can create volunteer statuses.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        $user->hasPermissionTo('create volunteer-types');
    }

    /**
     * Determine whether the user can update the volunteer status.
     *
     * @return mixed
     */
    public function update(User $user, VolunteerType $volunteerType)
    {
        $user->hasPermissionTo('update volunteer-types');
    }

    /**
     * Determine whether the user can delete the volunteer status.
     *
     * @return mixed
     */
    public function delete(User $user, VolunteerType $volunteerType)
    {
        $user->hasPermissionTo('delete volunteer-types');
    }

    /**
     * Determine whether the user can restore the volunteer status.
     *
     * @return mixed
     */
    public function restore(User $user, VolunteerType $volunteerType)
    {
        $user->hasPermissionTo('delete volunteer-types');
    }

    /**
     * Determine whether the user can permanently delete the volunteer status.
     *
     * @return mixed
     */
    public function forceDelete(User $user, VolunteerType $volunteerType)
    {
        $user->hasPermissionTo('delete volunteer-types');
    }
}
