<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VolunteerPolicy extends UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the app user.
     *
     * @return mixed
     */
    public function view(User $user, User $volunteer): bool
    {
        if (parent::view($user, $volunteer)) {
            return true;
        }

        return $user->id == $volunteer->id;
    }

    /**
     * Determine whether the user can create app users.
     *
     * @return mixed
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the app user.
     *
     * @return mixed
     */
    public function update(User $user, User $volunteer): bool
    {
        if (parent::update($user, $volunteer)) {
            return true;
        }

        return $user->id == $volunteer->id;
    }
}
