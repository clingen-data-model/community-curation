<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Lab404\Impersonate\Models\Impersonate;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use CrudTrait;
    use Impersonate;
    use LogsActivity;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canImpersonate()
    {
        return $this->can('impersonate');
    }

    public function canBeImpersonated()
    {

        if ($this->hasRole('programmer')) {
            return false;
        }

        if (\Auth::user()->hasRole('programmer')) {
            return true;
        }

        // If the user has any role that matches the authed user's roles this user cannot be impersonated
        if (\Auth::user()->roles->intersect($this->roles)->count() > 0) {
            return false;
        }

        return true;
    }
    
    
}
