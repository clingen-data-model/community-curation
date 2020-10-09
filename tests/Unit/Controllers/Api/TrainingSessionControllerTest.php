<?php

namespace Tests\Unit\Controllers\Api;

use App\CurationActivity;
use App\TrainingSession;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\TrainingSessions\TrainingSessionTestCase;

/**
 * @group training
 * @group training-sessions
 */
class TrainingSessionControllerTest extends TrainingSessionTestCase
{
    use DatabaseTransactions;

    public function setup(): void
    {
        parent::setup();
        $this->admin = $this->createAdmin();
    }

    /**
     * @test
     */
    public function index_returns_all_upcoming_training_sessions()
    {
        $future = factory(TrainingSession::class, 3)->states('future')->create()->sortBy('starts_at');
        $future->load('topic');
        $past = factory(TrainingSession::class, 1)->states('past')->create()->sortBy('starts_at');
        $past->load('topic');

        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->admin, 'api')
            ->call('GET', '/api/training-sessions');

        $this->assertEquals($response->original->pluck('id'), $future->pluck('id'));
        $this->assertNotContains($response->original->pluck('id'), $past->pluck('id'));
    }

    /**
     * @test
     */
    public function index_returns_past_events_if_requested()
    {
        $past = factory(TrainingSession::class, 3)->states('past')->create()->sortBy('starts_at');
        $past->load('topic');
        $future = factory(TrainingSession::class, 1)->states('future')->create()->sortBy('starts_at');
        $future->load('topic');

        $response = $this->actingAs($this->admin, 'api')
            ->call('GET', '/api/training-sessions?scopes[]=past');

        $this->assertEquals($response->original->pluck('id'), $past->pluck('id'));
        $this->assertNotContains($response->original->pluck('id'), $future->pluck('id'));
    }

    /**
     * @test
     */
    public function admin_and_programmer_can_store_new_training_sessions()
    {
        $admin = $this->createAdmin();
        $startsAt = Carbon::now()->addWeeks(4);
        $response = $this->actingAs($admin, 'api')
            ->call('POST', '/api/training-sessions', [
                'topic_type' => CurationActivity::class,
                'topic_id' => 1,
                'starts_at' => $startsAt,
                'ends_at' => $startsAt->clone()->addHour(),
                'url' => 'https://test@example.com',
                'invite_message' => 'test test test',
                'notes' => 'notes notes notes',
            ]);
        // ->assertStatus(201);

        $this->assertDatabaseHas('training_sessions', [
            'topic_type' => CurationActivity::class,
            'topic_id' => 1,
            'starts_at' => $startsAt->format('Y-m-d H:i:s'),
            'ends_at' => $startsAt->clone()->addHour()->format('Y-m-d H:i:s'),
            'invite_message' => 'test test test',
            'notes' => 'notes notes notes',
        ]);

        $response->assertJson([
            'data' => [
                'topic_type' => CurationActivity::class,
                'topic_id' => 1,
                'starts_at' => $startsAt->format('Y-m-d\TH:i:s\Z'),
                'ends_at' => $startsAt->clone()->addHour()->format('Y-m-d\TH:i:s\Z'),
                'url' => 'https://test@example.com',
                'invite_message' => '<p>test test test</p>',
                'notes' => 'notes notes notes',
            ],
        ]);
    }

    /**
     * @test
     */
    public function volunteers_cannot_store_new_training_sessions()
    {
        $startsAt = Carbon::now()->addWeeks(4);
        $response = $this->actingAs($this->createVolunteer(), 'api')
            ->call('POST', '/api/training-sessions', [
                'topic_type' => CurationActivity::class,
                'topic_id' => 1,
                'starts_at' => $startsAt,
                'ends_at' => $startsAt->clone()->addHour(),
                'url' => 'https://test@example.com',
                'invite_message' => 'test test test',
                'notes' => 'notes notes notes',
            ])
            ->assertRedirect('/');
    }

    /**
     * @test
     */
    public function it_validates_required_fields()
    {
        // $this->withoutExceptionHandling();
        $response = $this->actingAs($this->createAdmin(), 'api')
            ->json('POST', '/api/training-sessions', []);
        $response->assertStatus(422);

        $this->assertEquals($response->original['errors'], [
            'topic_type' => ['The topic type field is required.'],
            'topic_id' => ['A topic is required.'],
            'url' => ['A URL is required.'],
            'starts_at' => ['A start date and time are required.'],
            'ends_at' => ['An end date and time are required.'],
        ]);
    }

    /**
     * @test
     */
    public function it_validates_valid_topic_types()
    {
        $response = $this->actingAs($this->createAdmin(), 'api')
            ->json('POST', '/api/training-sessions', [
                'topic_type' => 'App\User',
            ]);
        $response->assertStatus(422);

        $this->assertEquals($response->original['errors']['topic_type'], ['The topic type is not valid.']);
    }

    /**
     * @test
     */
    public function it_validates_dates()
    {
        $response = $this->actingAs($this->createAdmin(), 'api')
            ->json('POST', '/api/training-sessions', [
                'starts_at' => 'beans',
                'ends_at' => 'monkeys',
            ]);
        $response->assertStatus(422);

        $this->assertContains('You must provide a valid start date and time.', $response->original['errors']['starts_at']);
        $this->assertContains('You must provide a valid end date and time.', $response->original['errors']['ends_at']);

        $response = $this->actingAs($this->createAdmin(), 'api')
            ->json('POST', '/api/training-sessions', [
                'starts_at' => '2020-01-01 01:01:01',
                'ends_at' => '2020-01-01 00:01:01',
            ]);
        $response->assertStatus(422);

        $this->assertContains('End date and time must be after start date and time.', $response->original['errors']['ends_at']);
    }

    /**
     * @test
     */
    public function it_validates_for_a_valid_url()
    {
        $response = $this->actingAs($this->createAdmin(), 'api')
            ->json('POST', '/api/training-sessions', [
                'url' => 'monkeys',
            ]);
        $response->assertStatus(422);

        $this->assertContains('url', array_keys($response->original['errors']));
    }

    public function a_user_can_view_a_TrainingSession()
    {
        $trainingSession = factory(TrainingSession::class)->create();

        $response = $this->actingAs($this->createVolunteer(), 'api')
            ->get('/api/training-sessions/'.$trainingSession->id)
            ->assertJson(['data' => $trainingSession->toArray()]);
    }

    /**
     * @test
     */
    public function an_admin_or_programmer_can_update_a_trainingSesssion()
    {
        $startsAt = Carbon::now();
        $trainingSession = factory(TrainingSession::class)->create(['starts_at' => $startsAt, 'ends_at' => $startsAt->clone()->addHour()]);
        $attributes = $trainingSession->getAttributes();
        $attributes['starts_at'] = $startsAt->addDays(30)->format('Y-m-d H:i:s');
        $attributes['ends_at'] = $startsAt->clone()->addHour()->format('Y-m-d H:i:s');

        $response = $this->actingAs($this->createProgrammer(), 'api')
                        ->json('PUT', '/api/training-sessions/'.$trainingSession->id, $attributes);

        $this->assertDatabaseHas('training_sessions', [
            'id' => $trainingSession->id,
            'starts_at' => $startsAt->format('Y-m-d H:i:s'),
            'ends_at' => $startsAt->clone()->addHour()->format('Y-m-d H:i:s'),
        ]);

        $freshTraining = $trainingSession->fresh();

        $response->assertJson(['data' => [
                                            'id' => $freshTraining->id,
                                            'starts_at' => $startsAt->format('Y-m-d\TH:i:s\Z'),
                                            'ends_at' => $startsAt->clone()->addHour()->format('Y-m-d\TH:i:s\Z'),
                                        ],
                            ]);
    }

    /**
     * @test
     */
    public function an_admin_or_programmer_can_delete_a_training_session()
    {
        $trainingSession = factory(TrainingSession::class)->create();

        $this->actingAs($this->createAdmin(), 'api')
            ->json('DELETE', '/api/training-sessions/'.$trainingSession->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('training_sessions', [
            'id' => $trainingSession->id,
            'deleted_at' => null,
        ]);
    }
}
