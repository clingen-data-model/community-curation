<?php

namespace App;

use App\Events\AssignmentCreated;
use App\Events\TrainingCompleted;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;
use App\Collections\AssignmentCollection;

class Assignment extends Model
{
    protected $fillable = [
        'user_id',
        'assignment_status_id',
        'assignable_id',
        'assignable_type'
    ];

    protected $with = [
        'status',
        'assignable'
    ];

    protected $dispatchesEvents = [
        'created' => AssignmentCreated::class
    ];

    public static function boot()
    {
        parent::boot();

        static::updated(function ($model) {
            if ($model->isDirty('trained_at') && (!isset($model->getOriginal()['trained_at']) || is_null($model->getOriginal()['trained_at']))) {
                Event::dispatch(new TrainingCompleted($model));
            }
        });
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
    
    public function scopeCurationActivity($query)
    {
        return $query->where('assignable_type', CurationActivity::class);
    }

    public function scopeExpertPanel($query)
    {
        return $query->where('assignable_type', ExpertPanel::class);
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
