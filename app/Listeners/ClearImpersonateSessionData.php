<?php

namespace App\Listeners;

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
    }

    /**
     * Handle the event.
     */
    public function handle(LeaveImpersonation $event): void
    {
        session()->forget('app_impersonate_required_info_bypass');
    }
}
