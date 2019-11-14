<?php

namespace App\Contracts;

use App\Training;
use Illuminate\Database\Eloquent\Relations\Relation;

interface TrainingSubjectContract
{
    public function trainings(): Relation;
    public function getBasicTraining(): Training;
}