<?php

namespace App\Listeners;

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

        $attestation->user->aptitudes()->syncWithoutDetaching([$attestation->aptitude->id]);

    }
}
