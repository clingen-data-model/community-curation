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
     */
    public function viewAny(User $user): bool
    {
        $user->hasPermissionTo('list volunteer-types');
    }

    /**
     * Determine whether the user can view the volunteer status.
     */
    public function view(User $user, VolunteerType $volunteerType): bool
    {
        $user->hasPermissionTo('list volunteer-types');
    }

    /**
     * Determine whether the user can create volunteer statuses.
     */
    public function create(User $user): bool
    {
        $user->hasPermissionTo('create volunteer-types');
    }

    /**
     * Determine whether the user can update the volunteer status.
     */
    public function update(User $user, VolunteerType $volunteerType): bool
    {
        $user->hasPermissionTo('update volunteer-types');
    }

    /**
     * Determine whether the user can delete the volunteer status.
     */
    public function delete(User $user, VolunteerType $volunteerType): bool
    {
        $user->hasPermissionTo('delete volunteer-types');
    }

    /**
     * Determine whether the user can restore the volunteer status.
     */
    public function restore(User $user, VolunteerType $volunteerType): bool
    {
        $user->hasPermissionTo('delete volunteer-types');
    }

    /**
     * Determine whether the user can permanently delete the volunteer status.
     */
    public function forceDelete(User $user, VolunteerType $volunteerType): bool
    {
        $user->hasPermissionTo('delete volunteer-types');
    }
}
