<?php

namespace App\Listeners;

use App\Events\AttestationSigned;

class setHypothesisIdFromBaselineAttestation
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
    public function handle(AttestationSigned $event): void
    {
        if (! in_array($event->attestation->aptitude_id, config('aptitudes.baseline'))) {
            return;
        }

        if (! isset($event->attestation->data['hypothesis_id'])) {
            return;
        }

        $event->attestation
            ->user
            ->update([
                'hypothesis_id' => $event->attestation->data['hypothesis_id'],
            ]);
    }
}
