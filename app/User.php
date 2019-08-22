<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Spatie\Activitylog\Traits\LogsActivity;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use RevisionableTrait;
    use Notifiable;
    use CrudTrait;
    use Impersonate;
    use LogsActivity;
    use HasRoles;
    use SoftDeletes;

    protected $revisionCreationsEnabled = true;

    protected $allPermissions;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'volunteer_status_id',
        'volunteer_type_id',
        'street1',
        'street2',
        'city',
        'state',
        'zip',
        'country_id',
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

    static public function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if ($model->password == null) {
                $model->password = uniqid();
            }
        });
    }
    
    public function volunteerType()
    {
        return $this->belongsTo(VolunteerType::class);
    }

    public function volunteerStatus()
    {
        return $this->belongsTo(VolunteerStatus::class);
    }
    
    public function curationActivities()
    {
        return $this->belongsToMany(CurationActivity::class);
    }
    
    public function expertPanels()
    {
        return $this->belongsToMany(ExpertPanel::class);
    }

    public function application()
    {
        return $this->morphOne(Application::class, 'respondent');
    }
    
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
    

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
    
    public function scopeIsVolunteer($query)
    {
        return $query->role('volunteer');
    }
    

    public function hasPermissionTo($permString)
    {
        return $this->getAllPermissions()->contains('name', $permString);
    }
   
    public function getAllPermissions() 
    {
        if (is_null($this->allPermissions)) {
            $permissions = $this->permissions;

            if ($this->roles) {
                $permissions = $permissions->merge($this->getPermissionsViaRoles());
            }

            $this->allPermissions = $permissions->sort()->values();
        }
        
        return $this->allPermissions;
    }

    public function getAddressAttribute()
    {
        return [
            'street1' => $this->street1,
            'street2' => $this->street2,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'country_id' => $this->country_id,
            'country' => $this->country->name,
        ];
    }
    
    
}
