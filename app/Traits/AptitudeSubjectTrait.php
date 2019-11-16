<?php

namespace App\Traits;

use App\Aptitude;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Methods that fulfill AptitudeSubjectContract
 */
trait AptitudeSubjectTrait
{
    public function aptitudes() :Relation
    {
        return $this->morphMany(Aptitude::class, 'subject');
    }

    public function getBasicAptitude() :Aptitude
    {
        return $this->aptitudes->first();
    }    
}
