<?php

namespace App;

use App\Contracts\IsNotable;
use App\Events\Volunteers\ConvertedToComprehensive;
use App\Events\Volunteers\MarkedBaseline;
use App\Events\Volunteers\MarkedDeclined;
use App\Events\Volunteers\MarkedUnresponsive;
use App\Events\Volunteers\Retired;
use App\Traits\IsNotable as TraitsIsNotable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use JsonException;
use Lab404\Impersonate\Models\Impersonate;
use Lab404\Impersonate\Services\ImpersonateManager;
use Laravel\Passport\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * User model class.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class User extends Authenticatable implements IsNotable
{
    use RevisionableTrait;
    use Notifiable;
    use CrudTrait;
    use Impersonate;
    use LogsActivity;
    use HasRoles;
    use SoftDeletes;
    use HasApiTokens;
    use TraitsIsNotable;

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
        'hypothesis_id',
        'password',
        'volunteer_status_id',
        'volunteer_type_id',
        'already_clingen_member',
        'already_member_cgs',
        'institution',
        'street1',
        'street2',
        'city',
        'state',
        'zip',
        'country_id',
        'timezone',
        'hypothesis_id',
        'last_logged_in_at',
        'last_logged_out_at',
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
        'already_member_cgs' => 'array',
    ];

    protected $appends = [
        'name',
    ];

    protected $dates = [
        'last_logged_in_at',
        'last_logged_out_at',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if ($model->password == null) {
                $model->password = uniqid();
            }

            // if creating volunteer and no status set assign status applied
            if (!is_null($model->volunteer_type_id) && is_null($model->volunteer_status_id)) {
                $model->volunteer_status_id = config('project.volunteer-statuses.applied');
            }
        });
        static::saved(function ($model) {
            if ($model->isDirty('volunteer_status_id')) {
                switch ($model->volunteer_status_id) {
                    case config('volunteers.statuses.retired'):
                        Event::dispatch(new Retired($model));
                        break;
                    case config('volunteers.statuses.unresponsive'):
                        Event::dispatch(new MarkedUnresponsive($model));
                        break;
                    case config('volunteers.statuses.declined'):
                        Event::dispatch(new MarkedDeclined($model));
                        break;
                    default:
                        break;
                }
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

        static::deleted(function ($model) {
            $app = $model->application;
            if ($app) {
                $app->delete();
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

    public function curationGroups()
    {
        return $this->belongsToMany(CurationGroup::class);
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
                    'assignable',
                    'subAssignments',
                    'subAssignments.assignable',
                    // 'userAptitudes',             // moved to VolunteerController@show for more efficient index listing
                    // 'userAptitudes.aptitude',    // moved to VolunteerController@show for more efficient index listing
                    // 'userAptitudes.attestation', // moved to VolunteerController@show for more efficient index listing
                    // 'assignable.aptitudes',      // moved to VolunteerController@show for more efficient index listing
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

    public function curationGroupAssignments()
    {
        return $this->hasMany(Assignment::class)
            ->curationGroup();
    }

    public function preferences()
    {
        return $this->hasMany(UserPreference::class);
    }

    public function priorities()
    {
        return $this->hasMany(Priority::class);
    }

    public function trainingSessions()
    {
        return $this->belongsToMany(TrainingSession::class, 'training_session_user', 'user_id', 'training_session_id');
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

        if (Auth::user()->hasRole('admin') && $this->hasAnyRole(['programmer', 'super-admin'])) {
            return false;
        }
        if (Auth::user()->roles->intersect($this->roles)->count() > 0) {
            return false;
        }

        return true;
    }

    public function getImpersonatedByAttribute()
    {
        if ($this->isImpersonated()) {
            return app(ImpersonateManager::class)->getImpersonator();
        }

        return null;
    }

    public function scopeIsVolunteer($query)
    {
        return $query->role('volunteer');
    }

    public function scopeComprehensive($query)
    {
        // dump(config('project.volunteer_types.comprehensive'));
        return $query->where('volunteer_type_id', config('project.volunteer_types.comprehensive'));
    }

    public function scopeIsLoggedIn($query)
    {
        return $query->where('last_logged_in_at', '<=', Carbon::now())
                    ->where(function ($q) {
                        $q->whereColumn('last_logged_out_at', '<=', 'last_logged_in_at')
                            ->orWhere(function ($qu) {
                                $qu->WhereNull('last_logged_out_at');
                            });
                    });
    }

    public function hasPermissionTo($permString)
    {
        return $this->getAllPermissions()->contains('name', $permString);
    }

    public function getAllPermissions()
    {
        if (is_null($this->allPermissions)) {
            $this->allPermissions = Cache::remember('user-'.$this->id.'-allPermissions', 60 * 20, function () {
                $permissions = $this->permissions;

                if ($this->roles) {
                    $permissions = $permissions->merge($this->getPermissionsViaRoles());
                }

                return $permissions->sort()->values();
            });
        }

        return $this->allPermissions;
    }

    public function getNameAttribute()
    {
        return trim($this->first_name.' '.$this->last_name);
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

    public function isAdminOrHigher()
    {
        return $this->hasAnyRole(['programmer', 'admin', 'super-admin']);
    }

    public function getTimezoneAttribute()
    {
        return $this->attributes['timezone'] ?? 'UTC';
    }

    public function getAlreadyMemberCurationGroups()
    {
        if (is_null($this->already_member_cgs)) {
            return collect();
        }

        try {
            $cgIds = $this->already_member_cgs;
            if (!$cgIds) {
                return collect();
            }

            return CurationGroup::find($cgIds);
        } catch (JsonException $th) {
            report($th);

            return collect();
        }

        return collect();
    }

    public function getMemberGroupsAttribute()
    {
        return $this->getAlreadyMemberCurationGroups();
    }

    public function hasAptitude($aptitudeId)
    {
        return $this->aptitudes()->where('id', $aptitudeId)->count() > 0;
    }

    public function isComprehensive()
    {
        return $this->hasRole('volunteer') && $this->volunteer_type_id == config('volunteers.types.comprehensive');
    }

    public function routeNotificationForSlack()
    {
        return config('logging.channels.slack.url');
    }

    public function hasRequiredInfo()
    {
        return $this->timezone != 'UTC' && $this->country_id !== null;
    }

    public function hasDemographicInfo()
    {
        return $this->application->highest_ed !== null
            && $this->application->race_ethnicity !== null
            && $this->application->self_desc !== null;
    }

    public function setPreference($preferenceName, $value)
    {
        $preference = Preference::where('name', $preferenceName)->firstOrFail();

        $this->preferences()->updateOrCreate([
            'preference_id' => $preference->id,
            'value' => $value,
        ]);

        return $this;
    }

    public function getPreference($preferenceName)
    {
        $userPreference = $this->preferences->keyBy('preference.name')->get($preferenceName);
        if ($userPreference) {
            return $userPreference->value;
        }

        $preference = Preference::findByName($preferenceName);

        return $preference->default;
    }
}
