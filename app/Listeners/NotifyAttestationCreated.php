<?php

namespace App\Listeners;

use App\Events\AttestationCreated;
use App\Notifications\AttestationCreatedNotification;

class NotifyAttestationCreated
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
    public function handle(AttestationCreated $event)
    {
        $event->attestation
            ->user
            ->notify(new AttestationCreatedNotification($event->attestation));
    }
}
