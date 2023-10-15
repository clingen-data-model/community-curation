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
     */
    public function handle(TrainingCreated $event): void
    {
        $userAptitude = $event->userAptitude;

        $userAptitude
            ->user
            ->notify(new TrainingAssignedNotification($userAptitude));
    }
}
