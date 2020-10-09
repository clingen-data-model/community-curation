<?php

namespace App\Listeners;

use App\Events\AttestationSigned;

class SetAssignmentStatusToTrained
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
    public function handle(AttestationSigned $event)
    {
        $assignment = $event->attestation->assignment;

        if ($assignment && $assignment->assignment_status_id == config('project.assignment-statuses.assigned')) {
            $assignment->update([
                'assignment_status_id' => config('project.assignment-statuses.trained'),
            ]);
        }
    }
}
