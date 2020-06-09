<?php

namespace App\Contracts;

use App\TrainingSession;

interface TrainingTopicContract
{
    public function trainingSessions();
    public function getName();
}
