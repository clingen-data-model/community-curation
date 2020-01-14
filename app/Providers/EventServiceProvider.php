<?php

namespace App\Providers;

use App\Events\AttestationCreated;
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
       'App\Events\Volunteers\Retired' => [
            'App\Listeners\Volunteers\RetireAssignments'
        ],
        'App\Events\AssignmentCreated' => [
            'App\Listeners\CreateCorrespondingTraining'
        ],
        'App\Events\TrainingCreated' => [
            'App\Listeners\NotifyTrainingAssigned'
        ],
        'App\Events\TrainingCompleted' => [
            'App\Listeners\CreateAttestationForCompletedTraining',
            'App\Listeners\SetVolunteerStatusToTrained',
        ],
        AttestationCreated::class => [
            'App\Listeners\NotifyAttestationCreated'
        ],
        'App\Events\AttestationSigned' => [
            'App\Listeners\GrantAptitudeForSignedAttestation',
            'App\Listeners\SetAssignmentStatusToTrained'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
