<?php

namespace App\Listeners\User;

use Carbon\Carbon;
use Illuminate\Auth\Events\Logout;
use App\User;

class SetLastLoggedOut
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
    public function handle(Logout $event)
    {
        if (! $event->user) { return; } // Avoid when session expired already
        if ($event->user instanceof User) {
            $event->user->forceFill([
                'last_logged_out_at' => Carbon::now(),
            ])->save();
            return;
        }
    }
}
