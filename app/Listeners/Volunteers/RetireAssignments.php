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
     */
    public function handle(VolunteerStatusChanged $event): void
    {
        $event->volunteer
            ->assignments
            ->each
            ->update([
                'assignment_status_id' => config('project.assignment-statuses.retired'),
            ]);
    }
}
