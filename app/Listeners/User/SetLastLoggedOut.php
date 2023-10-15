<?php

namespace App\Listeners\User;

use Carbon\Carbon;
use Illuminate\Auth\Events\Logout;

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
    public function handle(Logout $event): void
    {
        $event->user->update(['last_logged_out_at' => Carbon::now()]);
    }
}
