<?php

namespace App\Listeners\Volunteers;

use App\CurationActivity;
use App\Events\Volunteers\MarkedBaseline;
use App\Jobs\AssignVolunteerToAssignable;

class AssignBaselineActivty
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
    public function handle(MarkedBaseline $event)
    {
        AssignVolunteerToAssignable::dispatch($event->volunteer, CurationActivity::findByName('Baseline'));
    }
}
