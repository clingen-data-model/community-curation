<?php

namespace App\Listeners;

use App\Events\TrainingCreated;
use App\Events\UserTrainingCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\TrainingAssignedNotification;

class NotifyTrainingAssigned
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
     * @param  TrainingCreated  $event
     * @return void
     */
    public function handle(UserTrainingCreated $event)
    {
        $userTraining = $event->userTraining;

        $userTraining
            ->user
            ->notify(new TrainingAssignedNotification($userTraining->training->subject->getBasicTraining()));
    }
}
