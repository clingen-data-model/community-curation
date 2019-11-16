<?php

namespace App;

use App\Events\TrainingCreated;
use App\Events\TrainingCompleted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Training extends Model
{
    use SoftDeletes;

    protected $table = 'trainings';

    protected $fillable = [
        'aptitude_id',
        'user_id',
        'assignment_id',
        'completed_at',
    ];

    protected $dates = [
        'completed_at'
    ];

    protected $dispatchesEvents = [
        'created' => TrainingCreated::class,
    ];

    public static function boot()
    {
        parent::boot();

        static::updated(function ($model) {
            if ($model->isDirty('completed_at') && (!isset($model->getOriginal()['completed_at']) || is_null($model->getOriginal()['completed_at']))) {
                \Event::dispatch(new TrainingCompleted($model));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function aptitude()
    {
        return $this->belongsTo(Aptitude::class);
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
