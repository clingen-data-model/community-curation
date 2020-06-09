<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingSession extends Model
{
    use SoftDeletes;

    public $fillable = [
        'topic_type',
        'topic_id',
        'starts_at',
        'ends_at',
        'url',
        'invite_message',
        'notes'
    ];

    public $dates = [
        'starts_at',
        'ends_at'
    ];

    public function topic()
    {
        return $this->morphTo();
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'training_session_user', 'training_session_id', 'user_id');
    }

    public function scopeFuture($query)
    {
        return $query->where('starts_at', '>=', Carbon::now());
    }

    public function scopePast($query)
    {
        return $query->where('starts_at', '<=', Carbon::now());
    }
}
