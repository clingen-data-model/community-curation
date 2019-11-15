<?php

namespace App\Traits;

use App\Training;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Methods that fulfill TrainingSubjectContract
 */
trait TrainingSubjectTrait
{
    public function trainings() :Relation
    {
        return $this->morphMany(Training::class, 'subject');
    }

    public function getBasicTraining() :Training
    {
        return $this->trainings->first();
    }    
}
