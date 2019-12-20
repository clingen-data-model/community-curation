<?php

namespace App\Policies;

use App\User;
use App\Upload;
use Illuminate\Auth\Access\HandlesAuthorization;

class UploadPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any uploads.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list uploads');
    }

    /**
     * Determine whether the user can view the upload.
     *
     * @param  \App\User  $user
     * @param  \App\Upload  $upload
     * @return mixed
     */
    public function view(User $user, Upload $upload)
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
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    
    /**
     * Determine whether the user can create uploads for others.
     *
     * @param  \App\User  $user
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
     *
     * @param  \App\User  $user
     * @param  \App\Upload  $upload
     * @return mixed
     */
    public function update(User $user, Upload $upload)
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
     *
     * @param  \App\User  $user
     * @param  \App\Upload  $upload
     * @return mixed
     */
    public function delete(User $user, Upload $upload)
    {
        if ($user->hasPermissionTo('delete uploads')) {
            return true;
        }

        // if ($user->id == $upload->user_id) {
        //     return true;
        // }

        return false;
    }

    /**
     * Determine whether the user can restore the upload.
     *
     * @param  \App\User  $user
     * @param  \App\Upload  $upload
     * @return mixed
     */
    public function restore(User $user, Upload $upload)
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
     *
     * @param  \App\User  $user
     * @param  \App\Upload  $upload
     * @return mixed
     */
    public function forceDelete(User $user, Upload $upload)
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
