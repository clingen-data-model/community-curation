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
        if ( class_implements($event->assignment->assignable, TrainingSubjectContract::class) ) {
            $event->assignment
                ->volunteer
                ->trainings()
                ->create([
                    'training_id' => $event->assignment->assignable->getBasicTraining()->id
                ]);
        }
    }
}
