<?php

namespace App\Listeners;

use App\Events\AttestationSigned;
use Carbon\Carbon;

class GrantAptitudeForSignedAttestation
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
     * @param  AttestatioSigned  $event
     * @return void
     */
    public function handle(AttestationSigned $event)
    {
        $attestation = $event->attestation;

        if ($attestation->userAptitude) {
            $evaluator = $attestation->userAptitude->getEvaluator();
            if ($evaluator->meetsCriteria()) {
                $attestation->userAptitude->update(['granted_at' => Carbon::now()]);
            }
        }
    }
}
