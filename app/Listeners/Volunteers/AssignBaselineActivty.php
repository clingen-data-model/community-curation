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
     */
    public function handle(MarkedBaseline $event): void
    {
        AssignVolunteerToAssignable::dispatch($event->volunteer, CurationActivity::findByName('baseline'));
    }
}
