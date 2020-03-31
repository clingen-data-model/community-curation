<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Events\AttestationSigned;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GrantAptitudeForSignedAttestation
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
