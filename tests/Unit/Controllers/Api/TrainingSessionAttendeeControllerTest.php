<?php

namespace Tests\Unit\Controllers\Api;

use App\Jobs\AssignVolunteerToAssignable;
use App\Notifications\CustomTrainingEmail;
use App\TrainingSession;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\Feature\TrainingSessions\TrainingSessionTestCase;

/**
 * @group training-sessions
 */
class TrainingSessionAttendeeControllerTest extends TrainingSessionTestCase
{
    use DatabaseTransactions;

    public function setup(): void
    {
        parent::setup();
        $this->trainingSession = factory(TrainingSession::class)->create();
        [$this->vol1, $this->vol2, $this->vol3] = factory(User::class, 3)->states(['volunteer'])->create();
    }

    /**
     * @test
     */
    public function volunteer_can_not_invite_attendees()
    {
        // $this->withoutExceptionHandling();
        $this->actingAs($this->createVolunteer(), 'api')
            ->json('POST', '/api/training-sessions/'.$this->trainingSession->id.'/attendees', [$this->vol1->id, $this->vol2->id])
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function admin_can_invite_attendees()
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->createAdmin(), 'api')
            ->json('POST', '/api/training-sessions/'.$this->trainingSession->id.'/attendees', ['attendee_ids' => [$this->vol1->id, $this->vol2->id]])
            ->assertStatus(200);

        $this->assertDatabaseHas('training_session_user', [
            'training_session_id' => $this->trainingSession->id,
            'user_id' => $this->vol1->id,
        ]);
        $this->assertDatabaseHas('training_session_user', [
            'training_session_id' => $this->trainingSession->id,
            'user_id' => $this->vol2->id,
        ]);
        $this->assertDatabaseMissing('training_session_user', [
            'training_session_id' => $this->trainingSession->id,
            'user_id' => $this->vol3->id,
        ]);

        $response->assertJson(['data' => [
            ['id' => $this->vol1->id, 'first_name' => $this->vol1->first_name],
            ['id' => $this->vol2->id, 'first_name' => $this->vol2->first_name],
        ],
        ]);
    }

    /**
     * @test
     */
    public function admin_or_programmer_can_get_list_of_attendees_for_a_training_session()
    {
        $this->trainingSession->attendees()->sync([$this->vol1->id, $this->vol3->id]);

        $this->actingAs($this->createAdmin(), 'api')
            ->json('GET', '/api/training-sessions/'.$this->trainingSession->id.'/attendees/')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => $this->vol1->id,
                        'first_name' => $this->vol1->first_name,
                    ],
                    [
                        'id' => $this->vol3->id,
                        'first_name' => $this->vol3->first_name,
                    ],
                ],
            ]);
    }

    /**
     * @test
     */
    public function admin_can_remove_attendee_from_training_session()
    {
        $this->trainingSession->attendees()->sync([$this->vol1->id, $this->vol2->id, $this->vol3->id]);

        $this->actingAs($this->createAdmin(), 'api')
            ->json('DELETE', '/api/training-sessions/'.$this->trainingSession->id.'/attendees/'.$this->vol2->id);

        $this->assertDatabaseMissing('training_session_user', [
            'user_id' => $this->vol2->id,
            'training_session_id' => $this->trainingSession->id,
        ]);
    }

    /**
     * @test
     */
    public function gets_trainableVolunteers_for_training_session()
    {
        $volunteers = $this->createVolunteer(['volunteer_type_id' => 2], 3);
        $volunteers->slice(0, 2)->each(function ($vol) {
            AssignVolunteerToAssignable::dispatch($vol, $this->trainingSession->topic);
        });

        $response = $this->actingAs($this->createAdmin(), 'api')
            ->json('GET', '/api/training-sessions/'.$this->trainingSession->id.'/trainable-volunteers')
            ->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'id' => $volunteers->get(0)->id,
                    'first_name' => $volunteers->get(0)->first_name,
                ],
                [
                    'id' => $volunteers->get(1)->id,
                    'first_name' => $volunteers->get(1)->first_name,
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function sends_custom_email_to_attendees()
    {
        $this->trainingSession->attendees()->sync([$this->vol1->id, $this->vol3->id]);

        Notification::fake();

        $admin = $this->createAdmin();
        $bodyText = 'This is a <strong>test</strong> of the <a href="http://clinicalgenome.org/">Link</a>';
        $this->actingAs($admin, 'api')
            ->json(
                'POST',
                '/api/training-sessions/'.$this->trainingSession->id.'/attendees/email',
                [
                    'recipients' => $this->vol1->id.','.$this->vol3->id,
                    'from' => $admin->email,
                    'subject' => 'Test custom email',
                    'body' => $bodyText,
                ]
            )
            ->assertStatus(200);

        Notification::assertSentTo(
            [$this->vol1, $this->vol3],
            CustomTrainingEmail::class,
            function ($notification, $channels) use ($admin, $bodyText) {
                return $notification->fromEmail == $admin->email
                    && $notification->subject == 'Test custom email'
                    && $notification->body == '<p>'.$bodyText.'</p>';
            }
        );

        Notification::assertNotSentTo([$this->vol2], CustomTrainingEmail::class);
    }
}
