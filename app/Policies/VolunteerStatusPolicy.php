<?php

namespace App\Policies;

use App\User;
use App\VolunteerStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class VolunteerStatusPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any volunteer statuses.
     *
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        $user->hasPermissionTo('list volunteer-status');
    }

    /**
     * Determine whether the user can view the volunteer status.
     *
     * @return mixed
     */
    public function view(User $user, VolunteerStatus $volunteerStatus): bool
    {
        $user->hasPermissionTo('list volunteer-status');
    }

    /**
     * Determine whether the user can create volunteer statuses.
     *
     * @return mixed
     */
    public function create(User $user): bool
    {
        $user->hasPermissionTo('create volunteer-status');
    }

    /**
     * Determine whether the user can update the volunteer status.
     *
     * @return mixed
     */
    public function update(User $user, VolunteerStatus $volunteerStatus): bool
    {
        $user->hasPermissionTo('update volunteer-status');
    }

    /**
     * Determine whether the user can delete the volunteer status.
     *
     * @return mixed
     */
    public function delete(User $user, VolunteerStatus $volunteerStatus): bool
    {
        $user->hasPermissionTo('delete volunteer-status');
    }

    /**
     * Determine whether the user can restore the volunteer status.
     *
     * @return mixed
     */
    public function restore(User $user, VolunteerStatus $volunteerStatus): bool
    {
        $user->hasPermissionTo('delete volunteer-status');
    }

    /**
     * Determine whether the user can permanently delete the volunteer status.
     *
     * @return mixed
     */
    public function forceDelete(User $user, VolunteerStatus $volunteerStatus): bool
    {
        $user->hasPermissionTo('delete volunteer-status');
    }
}
