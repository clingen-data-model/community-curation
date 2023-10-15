<?php

namespace App\Listeners;

use App\Events\Assignments\GroupAssignmentCreated;

class MarkParticipantActive
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
    public function handle(GroupAssignmentCreated $event): void
    {
        if ($event->assignment->volunteer->volunteer_status_id != config('volunteers.statuses.active')) {
            $event->assignment->volunteer->update(['volunteer_status_id' => config('volunteers.statuses.active')]);
        }
    }
}
