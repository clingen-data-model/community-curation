<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group surveys
 */
class VolunteerThreeMonthFollowupTest extends TestCase
{
    use DatabaseTransactions;

    public function setup():void
    {
        parent::setup();
        $this->volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
    }

    /**
     * @test
     */
    public function unauthenticated_users_redirected_to_login()
    {
        $this->call('GET', $this->getUrl($this->volunteer))
            ->assertRedirect();
    }

    /**
     * @test
     */
    public function authenticated_users_can_start_a_response()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->volunteer)
            ->call('GET', $this->getUrl($this->volunteer))
            ->assertStatus(200);
    }
    

    /**
     * @test
     */
    public function volunteer_can_see_their_own_response()
    {
        $rsp = class_survey()::findBySlug('volunteer-three-month1')
                ->getNewResponse($this->volunteer);

        $this->actingAs($this->volunteer)
            ->call('GET', $this->getUrl($this->volunteer, $rsp->id))
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function volunteer_redirected_to_response_if_exists_and_id_not_given()
    {
        $rsp = class_survey()::findBySlug('volunteer-three-month1')
                ->getNewResponse($this->volunteer);

        $this->actingAs($this->volunteer)
            ->call('GET', $this->getUrl($this->volunteer))
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function volunteer_cannot_view_another_volunteers_resonse()
    {
        $rsp = class_survey()::findBySlug('volunteer-three-month1')
                ->getNewResponse($this->volunteer);
        $rsp->save();

        $vol2 = factory(User::class)->states(['volunteer', 'comprehensive'])->create();

        $this->actingAs($vol2)
            ->call('GET', $this->getUrl($this->volunteer, $rsp->id))
            ->assertStatus(403);
    }
    
    /**
     * @test
     */
    public function volunteer_cannot_update_a_finalized_response()
    {
        $rsp = class_survey()::findBySlug('volunteer-three-month1')
                ->getNewResponse($this->volunteer);
        $rsp->finalize();

        $this->actingAs($this->volunteer)
            ->call(
                'POST',
                $this->getUrl($this->volunteer, $rsp->id),
                ['highest_ed' => 1]
            )
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function volunteer_cannot_update_another_volunteers_response()
    {
        $rsp = class_survey()::findBySlug("volunteer-three-month1")
                ->getNewResponse($this->volunteer);
        $rsp->save();

        $vol2 = factory(User::class)->states(['volunteer', 'comprehensive'])->create([]);

        $this->actingAs($vol2)
            ->call('POST', $this->getUrl($this->volunteer, $rsp->id), ['highest_ed' => 1])
            ->assertStatus(403);
    }
    

    private function getUrl($volunteer, $rspId = null)
    {
        return 'volunteer-three-month'.(($rspId) ? '/'.$rspId : '');
    }
}
