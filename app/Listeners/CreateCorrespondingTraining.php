<?php

namespace App\Listeners;

use App\Contracts\TrainingSubjectContract;
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
        if (!is_subclass_of($event->assignment->assignable, TrainingSubjectContract::class)) {
            return;
        }

        $basicTraining = $event->assignment->assignable->getBasicTraining();

        if (!$basicTraining) {
            return;
        }

        $event->assignment
            ->volunteer
            ->userTrainings()
            ->create([
                'training_id' => $basicTraining->id,
                'assignment_id' => $event->assignment->id
            ]);
    }
}
