<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\Relation;

interface AptitudeSubjectContract
{
    public function aptitudes(): Relation;

    public function getPrimaryAptitude();

    public function getSecondaryAptitude();
}
