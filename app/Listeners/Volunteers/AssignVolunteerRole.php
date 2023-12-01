<?php

namespace App\Listeners\Volunteers;

use App\Events\Volunteers\Created;

class AssignVolunteerRole
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
     * @return void
     */
    public function handle(Created $event)
    {
        $event->volunteer->assignRole('volunteer');
    }
}
