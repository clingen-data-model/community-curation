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
     *
     * @return void
     */
    public function handle(TrainingCompleted $event)
    {
        if ($event->userAptitude->user->volunteer_status_id == config('volunteers.statuses.applied')) {
            $event->userAptitude->user->update(['volunteer_status_id' => config('volunteers.statuses.trained')]);
        }
    }
}
