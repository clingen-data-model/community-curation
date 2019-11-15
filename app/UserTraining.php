<?php

namespace App;

use App\Events\UserTrainingCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTraining extends Model
{
    use SoftDeletes;

    protected $table = 'training_user';

    protected $fillable = [
        'training_id',
        'user_id',
        'assignment_id',
        'completed_at',
    ];

    protected $dispatchesEvents = [
        'created' => UserTrainingCreated::class
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
    
    public function isComplete()
    {
        return (bool)$this->completed_at;
    }
    

}
