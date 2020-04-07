<?php

namespace App;

use App\Gene;
use Backpack\CRUD\CrudTrait;
use App\Events\Volunteers\Retired;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Spatie\Permission\Traits\HasRoles;
use App\Http\Resources\DefaultResource;
use Illuminate\Notifications\Notifiable;
use App\Events\Volunteers\MarkedBaseline;
use App\Http\Resources\AssignmentResource;
use Lab404\Impersonate\Models\Impersonate;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use App\Events\Volunteers\ConvertedToComprehensive;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * User model class
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class User extends Authenticatable
{
    use RevisionableTrait;
    use Notifiable;
    use CrudTrait;
    use Impersonate;
    use LogsActivity;
    use HasRoles;
    use SoftDeletes;
    use HasApiTokens;

    protected $revisionCreationsEnabled = true;

    protected $allPermissions;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'orcid_id',
        'password',
        'volunteer_status_id',
        'volunteer_type_id',
        'institution',
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

    protected $appends = [
        'name'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if ($model->password == null) {
                $model->password = uniqid();
            }
        });
        static::saved(function ($model) {
            if ($model->isDirty('volunteer_status_id')
                && $model->volunteer_status_id == config('volunteers.statuses.retired')
            ) {
                Event::dispatch(new Retired($model));
            }
            if ($model->isDirty('volunteer_type_id')) {
                if ($model->volunteer_type_id == config('volunteers.types.comprehensive')
                    && $model->getOriginal('volunteer_type_id') == config('volunteers.types.baseline')
                ) {
                    Event::dispatch(new ConvertedToComprehensive($model));
                }

                if ($model->volunteer_type_id == config('volunteers.types.baseline')) {
                    Event::dispatch(new MarkedBaseline($model));
                }
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

    public function volunteer3MonthSurvey()
    {
        return $this->morphOne(Volunteer3MonthSurvey::class, 'respondent');
    }
    
    public function volunteer6MonthSurvey()
    {
        return $this->morphOne(Volunteer6MonthSurvey::class, 'respondent');
    }
    
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function structuredAssignments()
    {
        return $this->assignments()
                ->curationActivity()
                ->with([
                    'status',
                    'userAptitudes',
                    'userAptitudes.aptitude',
                    'userAptitudes.attestation',
                    'assignable',
                    'assignable.aptitudes',
                    'subAssignments',
                    'subAssignments.assignable'
                ]);
    }

    public function attestations()
    {
        return $this->hasMany(Attestation::class);
    }
    
    public function aptitudes()
    {
        return $this->belongsToMany(Aptitude::class, 'user_aptitudes')
            ->withPivot([
                'assignment_id',
                'attestation_id',
                'trained_at',
                'granted_at',
            ])
            ->withTimestamps();
    }

    public function userAptitudes()
    {
        return $this->hasMany(UserAptitude::class);
    }
    
    public function curationActivityAssignments()
    {
        return $this->hasMany(Assignment::class)
            ->curationActivity();
    }

    public function expertPanelAssignments()
    {
        return $this->hasMany(Assignment::class)
            ->expertPanel();
    }

    public function priorities()
    {
        return $this->hasMany(Priority::class);
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

        if (Auth::user()->hasRole('programmer')) {
            return true;
        }
        if (Auth::user()->roles->intersect($this->roles)->count() > 0) {
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

    public function getNameAttribute()
    {
        return $this->first_name . ' '. $this->last_name;
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

    public function getLatestPrioritiesAttribute()
    {
        if ($this->priorities->count() == 0) {
            return collect([]);
        }
        return $this->priorities->groupBy('prioritization_round')->last();
    }

    public function getIsCoordinatorAttribute()
    {
        return false;
    }

    public function hasAptitude($aptitudeId)
    {
        return $this->aptitudes()->where('id', $aptitudeId)->count() > 0;
    }

    public function isComprehensive()
    {
        return $this->hasRole('volunteer') && $this->volunteer_type_id == config('volunteers.types.comprehensive');
    }
}
