<?php

namespace App\Traits;

/**
 * trait for a training topic.
 */
trait TrainingTopicTrait
{
    public function trainingSessions()
    {
        return $this->morphs(TrainingSession::class, 'topic');
    }

    public function getName()
    {
        return $this->name;
    }
}
