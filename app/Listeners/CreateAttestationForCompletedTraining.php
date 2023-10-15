<?php

namespace App\Listeners;

use App\Attestation;
use App\Events\TrainingCompleted;

class CreateAttestationForCompletedTraining
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
    public function handle(TrainingCompleted $event): void
    {
        $userAptitude = $event->userAptitude;

        if ($userAptitude->attestation_id) {
            return;
        }

        $attestation = Attestation::create([
            'user_id' => $userAptitude->user_id,
            'aptitude_id' => $userAptitude->aptitude_id,
            'assignment_id' => $userAptitude->assignment_id,
        ]);

        $userAptitude->fresh()->update(['attestation_id' => $attestation->id]);
    }
}
