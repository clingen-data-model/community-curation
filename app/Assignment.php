<?php

namespace App;

use App\Collections\AssignmentCollection;
use App\Events\AssignmentCreated;
use App\Events\TrainingCompleted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Event;

class Assignment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'assignment_status_id',
        'assignable_id',
        'assignable_type',
        'parent_id',
    ];

    protected $with = [
        'status',
        'assignable',
    ];

    protected $dispatchesEvents = [
        'created' => AssignmentCreated::class,
    ];

    public static function boot()
    {
        parent::boot();

        static::updated(function ($model) {
            if ($model->isDirty('trained_at') && (!isset($model->getOriginal()['trained_at']) || is_null($model->getOriginal()['trained_at']))) {
                Event::dispatch(new TrainingCompleted($model));
            }
        });

        static::deleting(function ($model) {
            if ($model->subAssignments->count() > 0) {
                $model->subAssignments->each->delete();
            }

            $model->volunteer->userAptitudes()
                    ->whereIn('aptitude_id', $model->assignable->aptitudes->pluck('id'))
                    ->get()
                    ->each
                    ->delete();
        });
    }

    /**
     * RELATIONS.
     */
    public function parent()
    {
        return $this->belongsTo(__CLASS__);
    }

    public function subAssignments()
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    public function status()
    {
        return $this->belongsTo(AssignmentStatus::class, 'assignment_status_id');
    }

    public function volunteer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignable()
    {
        return $this->morphTo();
    }

    public function attestations()
    {
        return $this->hasMany(Attestation::class);
    }

    public function userAptitudes()
    {
        return $this->hasMany(UserAptitude::class);
    }

    /**
     * SCOPES.
     */
    public function scopeIsParent($query)
    {
        return $query->curationActivity();
    }

    public function scopeHasParent($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeCurationActivity($query)
    {
        return $query->where('assignable_type', CurationActivity::class);
    }

    public function scopeCurationGroup($query)
    {
        return $query->where('assignable_type', CurationGroup::class);
    }

    public function scopeGene($query)
    {
        return $query->assignableType(Gene::class);
    }

    public function scopeAssignableType($query, $param)
    {
        return $query->where('assignable_type', $param);
    }

    public function scopeAssignableIs($query, $type, $id)
    {
        return $query->assignableType($type)
                ->where('assignable_id', $id);
    }

    public function newCollection(array $models = [])
    {
        return new AssignmentCollection($models);
    }

    public function assignableTypeIs($types)
    {
        $types = (is_string($types)) ? [$types] : $types;

        return in_array($this->assignable_type, $types);
    }
}
