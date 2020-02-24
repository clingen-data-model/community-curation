<?php

namespace App\Contracts;

use App\Aptitude;
use Illuminate\Database\Eloquent\Relations\Relation;

interface AptitudeSubjectContract
{
    public function aptitudes(): Relation;
    public function getBasicAptitude();
}