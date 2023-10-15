<?php

namespace App\Listeners;

use App\Events\TrainingCompleted;

class SetVolunteerStatusToTrained
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
    public function handle(TrainingCompleted $event): void
    {
        if ($event->userAptitude->user->volunteer_status_id == config('volunteers.statuses.applied')) {
            $event->userAptitude->user->update(['volunteer_status_id' => config('volunteers.statuses.trained')]);
        }
    }
}
