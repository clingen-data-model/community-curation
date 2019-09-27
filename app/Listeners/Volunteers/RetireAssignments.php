<?php

namespace App\Listeners\Volunteers;

use App\Events\Volunteers\Retired;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RetireAssignments
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
     * @param  Retired  $event
     * @return void
     */
    public function handle(Retired $event)
    {
        $event->volunteer
            ->assignments
            ->each
            ->update([
                'assignment_status_id' => config("project.assignment-statuses.retired")
            ]);
    }
}
