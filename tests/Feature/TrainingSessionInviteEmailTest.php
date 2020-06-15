<?php

namespace Tests\Feature;

use App\Jobs\InviteVolunteersToTrainingSession;
use Carbon\Carbon;
use Tests\TestCase;
use App\TrainingSession;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TrainingSessionInviteEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group training-sessions
 */
class TrainingSessionInviteEmailTest extends TestCase
{
    use DatabaseTransactions;

    public function setup():void
    {
        parent::setup();
        $this->vol1 = $this->createVolunteer();
        $this->vol2 = $this->createVolunteer();
        $this->trainingSession = factory(TrainingSession::class)->create([
            'starts_at' => Carbon::now()->addWeek(2)
        ]);
    }

    /**
     * @test
     */
    public function volunteer_sent_notification_when_invited_to_training_session()
    {
        Notification::fake();
        // $this->trainingSession->attendees()->syncWithoutDetaching([$this->vol1->id, $this->vol2->id]);
        InviteVolunteersToTrainingSession::dispatch($this->trainingSession, collect([$this->vol1, $this->vol2]));

        Notification::assertSentTo([$this->vol1, $this->vol2], TrainingSessionInviteEmail::class);
    }
}
