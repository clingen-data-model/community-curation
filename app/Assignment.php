<?php

namespace App;

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
        return false;
    }
}
