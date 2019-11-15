<?php

namespace App;

use App\Events\AssignmentCreated;
use Illuminate\Database\Eloquent\Model;

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

    public function training()
    {
        return $this->hasOne(UserTraining::class);
    }
    
    public function scopeCurationActivity($query)
    {
        return $query->where('assignable_type', CurationActivity::class);
    }

    public function scopeExpertPanel($query)
    {
        return $query->where('assignable_type', ExpertPanel::class);
    }

    public function getNeedsAptitudeAttribute()
    {
        if (!$this->training) {
            return false;
        }

        if ($this->training->isComplete()) {
            return false;
        }

        return true;
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
    
}
