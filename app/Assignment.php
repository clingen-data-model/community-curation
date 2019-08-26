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

    public function status()
    {
        return $this->belongsTo(AssignmentStatus::class);
    }

    public function volunteer()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function assignable()
    {
        return $this->morphTo();
    }
}
