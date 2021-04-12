<?php

namespace App\Traits;

use App\Aptitude;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Methods that fulfill AptitudeSubjectContract.
 */
trait AptitudeSubjectTrait
{
    public function aptitudes(): Relation
    {
        return $this->morphMany(Aptitude::class, 'subject');
    }

    public function getPrimaryAptitude()
    {
        return $this->aptitudes()->isPrimary()->first();
    }

    public function getSecondaryAptitude()
    {
        return $this->aptitudes()->isSecondary()->first();
    }
}
