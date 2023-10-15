<?php

namespace Tests\Feature;

use App\CurationActivity;
use App\CurationGroup;
use App\Priority;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PrioritiesTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->survey = class_survey()::findBySlug('priorities1');
        $this->user = factory(User::class)->create()->assignRole('volunteer');
    }

    /**
     * @test
     */
    public function can_reach_priorities_survey_with_a_respondent(): void
    {
        $this->call('GET', '/app-user/'.$this->user->id.'/survey/priorities1/new')
            ->assertStatus(302);

        $rsp = $this->survey->responses()->first();

        $this->call('GET', '/app-user/'.$this->user->id.'/survey/priorities1/'.$rsp->id)
            ->assertStatus(200)
            ->assertSee('You have chosen to become an active member of a curation group.');
    }

    /**
     * @test
     */
    public function redirects_to_thank_you_on_finalized(): void
    {
        $cA = CurationActivity::select('id')->get()->random();
        $ep = CurationGroup::factory()->create(['curation_activity_id' => $cA->id]);
        $data = [
            'curation_activity_1' => $cA->id,
            'panel_1' => $ep->id,
            'nav' => 'finalize',
        ];

        $this->call('POST', '/app-user/'.$this->user->id.'/survey/priorities1', $data)
            ->assertRedirect('/apply/thank-you');
    }

    /**
     * @test
     **/
    public function stores_priorities_with_new_priority_round(): void
    {
        Priority::factory(1)->create(['prioritization_round' => 1, 'user_id' => $this->user->id, 'curation_activity_id' => 5]);

        $rsp = $this->survey->getNewResponse($this->user);
        $rsp->curation_activity_1 = 1;
        $rsp->panel_1 = 1;
        $rsp->effort_experience_1 = 0;
        $rsp->activity_experience_1 = 0;
        $rsp->curation_activity_2 = 2;
        $rsp->panel_2 = 1;
        $rsp->effort_experience_2 = 0;
        $rsp->activity_experience_2 = 0;
        $rsp->save();

        $rsp->finalize();

        $this->assertDatabaseHas('priorities', [
            'user_id' => $this->user->id,
            'curation_activity_id' => 1,
            'prioritization_round' => 2,
        ]);
    }
}
