<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Lab404\Impersonate\Events\LeaveImpersonation;

class ClearImpersonateSessionData
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
     * @param  LeaveImpersonation  $event
     * @return void
     */
    public function handle(LeaveImpersonation $event)
    {
        session()->forget('app_impersonate_required_info_bypass');
    }
}
