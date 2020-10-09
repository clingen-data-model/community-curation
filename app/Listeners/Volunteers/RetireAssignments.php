<?php

namespace App\Listeners\Volunteers;

use App\Events\Volunteers\VolunteerStatusChanged;

class RetireAssignments
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
     * @param Retired $event
     *
     * @return void
     */
    public function handle(VolunteerStatusChanged $event)
    {
        $event->volunteer
            ->assignments
            ->each
            ->update([
                'assignment_status_id' => config('project.assignment-statuses.retired'),
            ]);
    }
}
