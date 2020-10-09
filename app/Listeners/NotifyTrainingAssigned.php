<?php

namespace App\Listeners;

use App\Events\TrainingCreated;
use App\Notifications\TrainingAssignedNotification;

class NotifyTrainingAssigned
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(TrainingCreated $event)
    {
        $training = $event->training;

        $training
            ->user
            ->notify(new TrainingAssignedNotification($training));
    }
}
