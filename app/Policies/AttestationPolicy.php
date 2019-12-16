<?php

namespace App\Policies;

use App\User;
use App\Attestation;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttestationPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any attestations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list attestations');
    }

    /**
     * Determine whether the user can view the attestation.
     *
     * @param  \App\User  $user
     * @param  \App\Attestation  $attestation
     * @return mixed
     */
    public function view(User $user, Attestation $attestation)
    {
        if ($user->hasPermissionTo('list attestations')) {
            return true;
        }

        return ($user->id === $attestation->user_id);
    }

    /**
     * Determine whether the user can create attestations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create attestations');
    }

    /**
     * Determine whether the user can update the attestation.
     *
     * @param  \App\User  $user
     * @param  \App\Attestation  $attestation
     * @return mixed
     */
    public function update(User $user, Attestation $attestation)
    {
        if ($user->hasPermissionTo('update attestations')) {
            return true;
        }

        return ($user->id === $attestation->user_id);
    }

    /**
     * Determine whether the user can delete the attestation.
     *
     * @param  \App\User  $user
     * @param  \App\Attestation  $attestation
     * @return mixed
     */
    public function delete(User $user, Attestation $attestation)
    {
        return $user->hasPermissionTo('delete attestations');
    }

    /**
     * Determine whether the user can restore the attestation.
     *
     * @param  \App\User  $user
     * @param  \App\Attestation  $attestation
     * @return mixed
     */
    public function restore(User $user, Attestation $attestation)
    {
        return $user->hasPermissionTo('create attestations');
    }

    /**
     * Determine whether the user can permanently delete the attestation.
     *
     * @param  \App\User  $user
     * @param  \App\Attestation  $attestation
     * @return mixed
     */
    public function forceDelete(User $user, Attestation $attestation)
    {
        return $user->hasPermissionTo('delete attestations');
    }
}
