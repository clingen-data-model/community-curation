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
        $userAptitude = $event->userAptitude;

        $userAptitude
            ->user
            ->notify(new TrainingAssignedNotification($userAptitude));
    }
}
