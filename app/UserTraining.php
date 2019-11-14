<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTraining extends Model
{
    use SoftDeletes;

    protected $table = 'training_user';

    protected $fillable = [
        'training_id',
        'user_id',
        'completed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function getAssignment()
    {
        return $this->user
                ->assignments()
                ->assignableIs(get_class($this->training->subject), $this->training->subject->id)
                ->first();
    }
    
}
