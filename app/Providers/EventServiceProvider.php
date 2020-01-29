<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
       \App\Events\Volunteers\Retired::class => [
            \App\Listeners\Volunteers\RetireAssignments::class
        ],
       \App\Events\Volunteers\ConvertedToComprehensive::class => [
            \App\Listeners\Volunteers\NotifyConversionToComprehensive::class
        ],
       \App\Events\Volunteers\Retired::class => [
            \App\Listeners\Volunteers\RetireAssignments::class
        ],
        \App\Events\AssignmentCreated::class => [
            \App\Listeners\CreateCorrespondingTraining::class
        ],
        \App\Events\TrainingCreated::class => [
            \App\Listeners\NotifyTrainingAssigned::class
        ],
        \App\Events\TrainingCompleted::class => [
            \App\Listeners\CreateAttestationForCompletedTraining::class,
            \App\Listeners\SetVolunteerStatusToTrained::class,
        ],
        \App\Events\AttestationCreated::class => [
            \App\Listeners\NotifyAttestationCreated::class
        ],
        \App\Events\AttestationSigned::class => [
            \App\Listeners\GrantAptitudeForSignedAttestation::class,
            \App\Listeners\SetAssignmentStatusToTrained::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMailLogging();
        parent::boot();
    }

    private function registerMailLogging()
    {
        if (!isset($this->listen['Illuminate\Mail\Events\MessageSent'])) {
            $this->listen['Illuminate\Mail\Events\MessageSent'] = [];
        }
        $this->listen['Illuminate\Mail\Events\MessageSent'] = ['App\Listeners\LogSentMessage'];
    }
}
