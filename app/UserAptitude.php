<?php

namespace App;

use Carbon\Carbon;
use App\Assignment;
use App\Events\TrainingCreated;
use App\Events\TrainingCompleted;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAptitude extends Model
{
    use SoftDeletes;

    public $fillable = [
        'user_id',
        'aptitude_id',
        'assignment_id',
        'trained_at',
        'attestation_id',
        'granted_at'
    ];

    public $dates = [
        'trained_at',
        'granted_at'
    ];

    private $evaluator;

    protected $dispatchesEvents = [
        'created' => TrainingCreated::class,
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

    /**
     * Relations
     */
    public function aptitude()
    {
        return $this->belongsTo(Aptitude::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function attestation()
    {
        return $this->belongsTo(Attestation::class);
    }

    /**
     * Scopes
     */
    public function scopeGranted($query)
    {
        return $query->whereNotNull('granted_at');
    }

    public function scopePending($query)
    {
        return $query->whereNull('granted_at');
    }

    public function scopeTrained($query)
    {
        return $query->whereNotNull('trained_at');
    }

    public function scopeNeedsTraining($query)
    {
        return $query->whereNull('trained_at');
    }

    private function getEvaluator()
    {
        if (is_null($this->_evaluator)) {
            $class = $this->aptitude->evaluator_class;
            $this->_evaluator = new $class($this);
        }

        return $this->_evaluator;
    }

    public function isGranted()
    {
        if (!is_null($this->granted_at)) {
            return true;
        }

        if ($this->getEvaluator()->meetsCriteria()) {
            $this->granted_at = Carbon::now();
            return true;
        }

        return false;
    }
}
