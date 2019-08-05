<?php

namespace App\Policies;

use App\User;
use App\ExpertPanel;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpertPanelPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any expert panels.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list expert-panels');
    }

    /**
     * Determine whether the user can view the expert panel.
     *
     * @param  \App\User  $user
     * @param  \App\ExpertPanel  $expertPanel
     * @return mixed
     */
    public function view(User $user, ExpertPanel $expertPanel)
    {
        return $user->hasPermissionTo('list expert-panels');
    }

    /**
     * Determine whether the user can create expert panels.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create expert-panels');
    }

    /**
     * Determine whether the user can update the expert panel.
     *
     * @param  \App\User  $user
     * @param  \App\ExpertPanel  $expertPanel
     * @return mixed
     */
    public function update(User $user, ExpertPanel $expertPanel)
    {
        return $user->hasPermissionTo('update expert-panels');
    }

    /**
     * Determine whether the user can delete the expert panel.
     *
     * @param  \App\User  $user
     * @param  \App\ExpertPanel  $expertPanel
     * @return mixed
     */
    public function delete(User $user, ExpertPanel $expertPanel)
    {
        return $user->hasPermissionTo('delete expert-panels');
    }

    /**
     * Determine whether the user can restore the expert panel.
     *
     * @param  \App\User  $user
     * @param  \App\ExpertPanel  $expertPanel
     * @return mixed
     */
    public function restore(User $user, ExpertPanel $expertPanel)
    {
        return $user->hasPermissionTo('delete expert-panels');
    }

    /**
     * Determine whether the user can permanently delete the expert panel.
     *
     * @param  \App\User  $user
     * @param  \App\ExpertPanel  $expertPanel
     * @return mixed
     */
    public function forceDelete(User $user, ExpertPanel $expertPanel)
    {
        return $user->hasPermissionTo('delete expert-panels');
    }
}
