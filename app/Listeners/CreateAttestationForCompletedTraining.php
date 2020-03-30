<?php

namespace App\Listeners;

use App\Attestation;
use App\Events\TrainingCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateAttestationForCompletedTraining
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
     * @param  TrainingCompleted  $event
     * @return void
     */
    public function handle(TrainingCompleted $event)
    {
        $training = $event->training;

        if ($training->attestation_id) {
            return;
        }

        $attestation = Attestation::create([
            'user_id' => $training->user_id,
            'aptitude_id' => $training->aptitude_id,
            'assignment_id' => $training->assignment_id
        ]);
        
        $training->update(['attestation_id' => $attestation->id]);
    }
}
