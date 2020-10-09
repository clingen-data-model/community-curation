<?php

namespace App\Listeners;

use App\CurationActivity;
use App\Events\AssignmentCreated;
use App\Events\Assignments\GroupAssignmentCreated;
use Illuminate\Contracts\Events\Dispatcher;

class DispatchAssignmentTypeEvent
{
    private $dispatcher;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(AssignmentCreated $event)
    {
        if ($event->assignment->assignable_type != CurationActivity::class) {
            $this->dispatcher->dispatch(new GroupAssignmentCreated($event->assignment));
        }
    }
}
