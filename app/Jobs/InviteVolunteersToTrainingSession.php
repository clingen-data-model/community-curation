<?php

namespace App\Jobs;

use App\Notifications\TrainingSessionInviteEmail;
use App\TrainingSession;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class InviteVolunteersToTrainingSession
{
    use Dispatchable;
    use Queueable;

    protected $trainingSession;

    protected $volunteers;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TrainingSession $trainingSession, Collection $volunteers)
    {
        $this->trainingSession = $trainingSession;
        $this->volunteers = $volunteers;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->trainingSession->attendees()->syncWithoutDetaching($this->volunteers->pluck('id'));
        if ($this->trainingSession->isUpcoming()) {
            Notification::send($this->volunteers, new TrainingSessionInviteEmail($this->trainingSession));
        }
    }
}
