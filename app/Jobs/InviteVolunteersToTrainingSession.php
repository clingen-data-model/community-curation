<?php

namespace App\Jobs;

use App\TrainingSession;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TrainingSessionInviteEmail;

class InviteVolunteersToTrainingSession
{
    use Dispatchable, Queueable;

    protected $trainingSession;

    protected $volunteers;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TrainingSession $trainingSession, Collection $volunteers)
    {
        //
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
        Notification::send($this->volunteers, new TrainingSessionInviteEmail($this->trainingSession));
    }
}
