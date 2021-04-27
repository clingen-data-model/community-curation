<?php

namespace App\Listeners\Users;

use App\events\Users\UserCreated;
use App\Events\Volunteers\Created;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DispatchUserTypeCreated
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
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        if (is_null($event->user->volunteer_type_id)) {
            return;
        }

        event(new Created($event->user));
    }
}
