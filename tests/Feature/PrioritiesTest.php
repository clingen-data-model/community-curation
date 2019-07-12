<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
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
    }

    /**
     * @test
     */
    public function can_reach_priorities_survey_with_a_respondent()
    {
        $user = factory(User::class)->create()->assignRole('volunteer');
        
        $this->call('GET', '/app-user/'.$user->id.'/survey/priorities1/new')
            ->assertStatus(302);

        $rsp = $this->survey->responses()->first();

        $this->call('GET', '/app-user/'.$user->id.'/survey/priorities1/'.$rsp->id)
            ->assertStatus(200)
            ->assertSee('You have chosen to become an active member of a curation group.');
    }
    
    
}
