<?php

namespace App\Listeners\User;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;

class SetLastLoggedInAt
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
    public function handle(Login $event): void
    {
        $user = Auth::user();
        $user->update(['last_logged_in_at' => Carbon::now()]);
    }
}
