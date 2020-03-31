<?php

namespace App\Listeners;

use App\Contracts\AptitudeSubjectContract;
use App\Events\AssignmentCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateCorrespondingTraining
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
     * @param  AssignmentCreated  $event
     * @return void
     */
    public function handle(AssignmentCreated $event)
    {
        if (!is_subclass_of($event->assignment->assignable, AptitudeSubjectContract::class)) {
            return;
        }

        $basicTraining = $event->assignment->assignable->getBasicAptitude();

        if (!$basicTraining) {
            return;
        }

        $event->assignment
            ->volunteer
            ->userAptitudes()
            ->create([
                'aptitude_id' => $basicTraining->id,
                'assignment_id' => $event->assignment->id
            ]);
    }
}
