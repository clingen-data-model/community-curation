<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\ExpertPanel;
use App\CurationActivity;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PrioritiesTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp():void
    {
        parent::setUp();
        $this->survey = class_survey()::findBySlug('priorities1');
        $this->user = factory(User::class)->create()->assignRole('volunteer');
    }

    /**
     * @test
     */
    public function can_reach_priorities_survey_with_a_respondent()
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
    public function redirects_to_thank_you_on_finalized()
    {
        $cA = CurationActivity::select('id')->get()->random();
        $data = [
            'curation_activity_1' => $cA->id,
            'panel_1' => ExpertPanel::forCurationActivity($cA->id)->get()->random()->id,
            'nav' => 'finalize'
        ];

        $this->call('POST', '/app-user/'.$this->user->id.'/survey/priorities1', $data)
            ->assertRedirect('/apply/thank-you');
    }
    
    
    
}
