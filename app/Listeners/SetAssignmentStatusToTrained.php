<?php

namespace App\Listeners;

use App\Events\AttestationSigned;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetAssignmentStatusToTrained
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
     * @param  AttestationSigned  $event
     * @return void
     */
    public function handle(AttestationSigned $event)
    {

        $assignment = $event->attestation->assignment;

        if ($assignment && $assignment->assignment_status_id == config('project.assignment-statuses.assigned')) {
            $assignment->update([
                'assignment_status_id' => config('project.assignment-statuses.trained')
            ]);
        }
    }
}
