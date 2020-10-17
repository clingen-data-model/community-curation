<?php

namespace Tests\Feature\TrainingSessions;

use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group training
 * @group training-sessions
 */
class TrainingSessionListTest extends TrainingSessionTestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function guests_do_not_see_trainings_link()
    {
        $this->call('GET', '/volunteers')
            ->assertDontSee('<a href="/trainings" class="nav-link">Trainings</a>');
    }

    /**
     * @test
     */
    public function admin_and_programmer_can_see_link_in_nav()
    {
        $this->actingAs($this->createAdmin())
            ->call('GET', '/volunteers')
            ->assertSee('<a href="/trainings" class="nav-link">Trainings</a>', false);

        $this->actingAs($this->createProgrammer())
            ->call('GET', '/volunteers')
            ->assertSee('<a href="/trainings" class="nav-link">Trainings</a>', false);
    }

    /**
     * @test
     */
    public function volunteer_does_not_see_link_in_nav()
    {
        $this->actingAs($this->createVolunteer())
            ->call('GET', '/volunteers')
            ->assertDontSee('<a href="/trainings" class="nav-link">Trainings</a>');
    }

    /**
     * @test
     */
    public function volunteer_redirected_to_their_detail_when_visiting_trainings()
    {
        // $this->withoutExceptionHandling();
        $this->actingAs($this->createVolunteer())
            ->call('GET', '/trainings')
            ->assertStatus(302);
    }
}
