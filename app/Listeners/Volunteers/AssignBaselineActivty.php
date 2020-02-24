<?php

namespace App\Listeners\Volunteers;

use App\CurationActivity;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Volunteers\MarkedBaseline;
use App\Jobs\AssignVolunteerToAssignable;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssignBaselineActivty
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
     * @param  MarkedBaseline  $event
     * @return void
     */
    public function handle(MarkedBaseline $event)
    {
        AssignVolunteerToAssignable::dispatch($event->volunteer, CurationActivity::findByName('baseline'));
    }
}
