<?php

namespace App\Aptitudes\Evaluators;

interface AptitudeEvaluatorContract
{
    public function meetsCriteria():bool;
}
