<?php

namespace App\Listeners;

use App\Events\TrainingCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetVolunteerStatusToTrained
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TrainingCompleted  $event
     * @return void
     */
    public function handle(TrainingCompleted $event)
    {
        if ($event->training->user->volunter_status_id == config('project.volunteer_statuses.applied')) {
            $event->training->user->update(['volunteer_status_id' => config('project.volunteer_statuses.trained')]);
        }
    }
}