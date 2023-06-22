<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Lab404\Impersonate\Events\LeaveImpersonation;

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
        \App\Events\AssignmentCreated::class => [
            \App\Listeners\CreateAptitudeForAssignment::class,
            \App\Listeners\DispatchAssignmentTypeEvent::class,
        ],

        \App\Events\Assignments\GroupAssignmentCreated::class => [
            \App\Listeners\MarkParticipantActive::class,
        ],

        \App\Events\AttestationCreated::class => [
            \App\Listeners\NotifyAttestationCreated::class,
        ],
        \App\Events\AttestationSigned::class => [
            \App\Listeners\GrantAptitudeForSignedAttestation::class,
            \App\Listeners\SetAssignmentStatusToTrained::class,
            \App\Listeners\setHypothesisIdFromBaselineAttestation::class,
        ],

        \App\Events\TrainingCreated::class => [
            \App\Listeners\NotifyTrainingAssigned::class,
        ],
        \App\Events\TrainingCompleted::class => [
            \App\Listeners\CreateAttestationForCompletedTraining::class,
            \App\Listeners\SetVolunteerStatusToTrained::class,
            \App\Actions\TrainingCertificateGenerate::class,
        ],

        \App\Events\Volunteers\ConvertedToComprehensive::class => [
            \App\Listeners\Volunteers\NotifyConversionToComprehensive::class,
        ],
        \App\Events\Volunteers\MarkedBaseline::class => [
            \App\Listeners\Volunteers\AssignBaselineActivty::class,
        ],
        \App\Events\Volunteers\Retired::class => [
            \App\Listeners\Volunteers\RetireAssignments::class,
        ],
        \App\Events\Volunteers\MarkedDeclined::class => [
            \App\Listeners\Volunteers\RetireAssignments::class,
        ],
        \App\Events\Volunteers\MarkedUnresponsive::class => [
            \App\Listeners\Volunteers\RetireAssignments::class,
        ],
        \App\Events\Volunteers\Created::class => [
            \App\Listeners\Volunteers\AssignVolunteerRole::class,
        ],
        \App\Events\Users\UserCreated::class => [
            \App\Listeners\Users\DispatchUserTypeCreated::class,
        ],
        LeaveImpersonation::class => [
            \App\Listeners\ClearImpersonateSessionData::class,
        ],
        Login::class => [
            \App\Listeners\User\SetLastLoggedInAt::class,
        ],
        Logout::class => [
            \App\Listeners\User\SetLastLoggedOut::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // $this->registerMailLogging();
        parent::boot();
    }

    private function registerMailLogging()
    {
        if (! isset($this->listen['Illuminate\Mail\Events\MessageSent'])) {
            $this->listen['Illuminate\Mail\Events\MessageSent'] = [];
        }
        $this->listen['Illuminate\Mail\Events\MessageSent'] = [\App\Listeners\LogSentMessage::class];
    }
}
