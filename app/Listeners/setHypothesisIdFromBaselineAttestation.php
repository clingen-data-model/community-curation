<?php

namespace App\Listeners;

use App\Events\AttestationSigned;

class SetHypothesisIdFromBaselineAttestation
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
    public function handle(AttestationSigned $event)
    {
        if ($event->attestation->aptitude_id !== config('project.aptitudes.baseline-basic-evidence')) {
            return;
        }

        if (!isset($event->attestation->data['hypothesis_id'])) {
            return;
        }

        $event->attestation
            ->user
            ->update([
                'hypothesis_id' => $event->attestation->data['hypothesis_id'],
            ]);
    }
}
