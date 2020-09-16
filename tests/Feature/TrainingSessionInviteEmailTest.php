<?php

namespace Tests\Feature;

use App\Jobs\InviteVolunteersToTrainingSession;
use App\Notifications\TrainingSessionInviteEmail;
use App\TrainingSession;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * @group training-sessions
 */
class TrainingSessionInviteEmailTest extends TestCase
{
    use DatabaseTransactions;

    public function setup(): void
    {
        parent::setup();
        $this->vol1 = $this->createVolunteer();
        $this->vol2 = $this->createVolunteer();
        $this->trainingSession = factory(TrainingSession::class)->create([
            'starts_at' => Carbon::now()->addWeek(2),
        ]);
    }

    /**
     * @test
     */
    public function volunteer_sent_notification_when_invited_to_future_training_session()
    {
        Notification::fake();
        // $this->trainingSession->attendees()->syncWithoutDetaching([$this->vol1->id, $this->vol2->id]);
        InviteVolunteersToTrainingSession::dispatch($this->trainingSession, collect([$this->vol1, $this->vol2]));

        Notification::assertSentTo([$this->vol1, $this->vol2], TrainingSessionInviteEmail::class);
    }

    /**
     * @test
     */
    public function volunteer_not_sent_notification_when_invited_to_past_training_session()
    {
        Notification::fake();
        $this->trainingSession->update(['starts_at' => Carbon::now()->subMinute(1)]);
        InviteVolunteersToTrainingSession::dispatch($this->trainingSession, collect([$this->vol1, $this->vol2]));

        Notification::assertNothingSent();
    }
}
