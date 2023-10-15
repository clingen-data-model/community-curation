<?php

namespace App\Policies;

use App\Upload;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UploadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any uploads.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list uploads');
    }

    /**
     * Determine whether the user can view the upload.
     */
    public function view(User $user, Upload $upload): bool
    {
        if ($user->hasPermissionTo('list uploads')) {
            return true;
        }

        if ($user->id == $upload->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create uploads.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create uploads for others.
     *
     * @return mixed
     */
    public function createForOthers(User $user)
    {
        if ($user->hasPermissionTo('create for others uploads')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the upload.
     */
    public function update(User $user, Upload $upload): bool
    {
        if ($user->hasPermissionTo('update uploads')) {
            return true;
        }

        if ($user->id == $upload->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the upload.
     */
    public function delete(User $user, Upload $upload): bool
    {
        if ($user->hasPermissionTo('delete uploads')) {
            return true;
        }

        if ($user->id == $upload->uploader_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the upload.
     */
    public function restore(User $user, Upload $upload): bool
    {
        if ($user->hasPermissionTo('delete uploads')) {
            return true;
        }

        if ($user->id == $upload->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the upload.
     */
    public function forceDelete(User $user, Upload $upload): bool
    {
        if ($user->hasPermissionTo('delete uploads')) {
            return true;
        }

        if ($user->id == $upload->user_id) {
            return true;
        }

        return false;
    }
}
