<?php

namespace App\Listeners\Volunteers;

use App\Events\Volunteers\Created;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
     * @param  Created  $event
     * @return void
     */
    public function handle(Created $event)
    {
        $event->volunteer->assignRole('volunteer');
    }
}
