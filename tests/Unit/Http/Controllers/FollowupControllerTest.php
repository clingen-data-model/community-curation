<?php

namespace Tests\Unit\Http\Controllers;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @group surveys
 */
class FollowupControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setup(): void
    {
        parent::setup();
        $this->volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
    }

    /**
     * @test
     */
    public function unauthenticated_users_redirected_to_login()
    {
        $this->call('GET', $this->getUrl())
            ->assertRedirect();
    }

    /**
     * @test
     */
    public function authenticated_users_can_start_a_response()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->volunteer)
            ->call('GET', $this->getUrl())
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
            ->call('GET', $this->getUrl($rsp->id))
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
            ->call('GET', $this->getUrl())
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

        $this->expectUnauthorized();
        $this->actingAs($vol2)
            ->call('GET', $this->getUrl($rsp->id));
    }

    /**
     * @test
     */
    public function volunteer_cannot_update_a_finalized_response()
    {
        $rsp = class_survey()::findBySlug('volunteer-three-month1')
                ->getNewResponse($this->volunteer);
        $rsp->finalize();

        $url = $this->getUrl($rsp->id);

        $this->expectUnauthorized();
        $response = $this->actingAs($this->volunteer)
            ->call(
                'POST',
                $url,
                ['highest_ed' => 1]
            );

        // $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function volunteer_cannot_update_another_volunteers_response()
    {
        $rsp = class_survey()::findBySlug('volunteer-three-month1')
                ->getNewResponse($this->volunteer);
        $rsp->save();

        $vol2 = factory(User::class)->states(['volunteer', 'comprehensive'])->create([]);

        $this->expectUnauthorized();
        $this->actingAs($vol2)
            ->call('POST', $this->getUrl($rsp->id), ['highest_ed' => 1])
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function loads_correct_survey()
    {
        $this->actingAs($this->volunteer)
            ->call('GET', $this->getUrl(null, 'volunteer-three-month1'))
            ->assertSee('3 months');

        $this->actingAs($this->volunteer)
            ->call('GET', $this->getUrl(null, 'volunteer-six-month1'))
            ->assertSee('6 months');
    }

    private function getUrl($rspId = null, $surveySlug = 'volunteer-three-month1')
    {
        return 'volunteer-followup/'.$surveySlug.(($rspId) ? '/'.$rspId : '');
    }
}
