<?php

namespace App\Policies;

use App\Attestation;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttestationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any attestations.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list attestations');
    }

    /**
     * Determine whether the user can view the attestation.
     */
    public function view(User $user, Attestation $attestation): bool
    {
        if ($user->hasPermissionTo('list attestations')) {
            return true;
        }

        return $user->id === $attestation->user_id;
    }

    /**
     * Determine whether the user can create attestations.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create attestations');
    }

    /**
     * Determine whether the user can update the attestation.
     */
    public function update(User $user, Attestation $attestation): bool
    {
        if ($user->hasPermissionTo('update attestations')) {
            return true;
        }

        return $user->id === $attestation->user_id;
    }

    /**
     * Determine whether the user can delete the attestation.
     */
    public function delete(User $user, Attestation $attestation): bool
    {
        return $user->hasPermissionTo('delete attestations');
    }

    /**
     * Determine whether the user can restore the attestation.
     */
    public function restore(User $user, Attestation $attestation): bool
    {
        return $user->hasPermissionTo('create attestations');
    }

    /**
     * Determine whether the user can permanently delete the attestation.
     */
    public function forceDelete(User $user, Attestation $attestation): bool
    {
        return $user->hasPermissionTo('delete attestations');
    }
}
