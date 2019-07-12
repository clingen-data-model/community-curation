<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApplicationTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp():void
    {
        parent::setUp();
        $this->survey = class_survey()::findBySlug('application1');
    }

    /**
     * @test
     */
    public function the_application_can_be_accessed_by_guests()
    {
        $this->call('GET', '/apply')
            ->assertStatus(200)
            ->assertSee('Introduction')
            ->assertSee('Thank you for your interest in volunteering as a curator for ClinGen.')
            ->assertSessionHas('application-response');
    }

    /**
     * @test
     */
    public function session_is_updated_when_application_is_saved()
    {
        $this->call('GET', '/apply')
            ->assertStatus(200);

        $this->call('POST', '/apply', ['nav' => 'next'])
            ->assertStatus(302)
            ->assertRedirect('apply/'.session()->get('application-response')->id.'?page=demographic')
            ->assertSessionHas('application-response');

        $this->assertNotNull(session()->get('application-response')->id);
    }

    /**
     * @test
     */
    public function shows_specific_response_if_id_provided()
    {
        $rsp = $this->survey->getNewResponse(null);
        $rsp->save();

        $httpResponse = $this->call('GET', 'apply/'.$rsp->id)
             ->assertStatus(200)
             ->assertSee('response_id: '.$rsp->id);
                
        $this->assertEquals(session()->get('application-response')->id, $rsp->id);


    }
    
    /**
     * @test
     */
    public function redirects_to_priorities_survey_for_new_respondent_if_comprehensive_volunteer()
    {
        $rsp = $this->survey->getNewResponse(null);
        $rsp->applicant_name = 'billy pilgrim';
        $rsp->email = 'beans@test.com';
        $rsp->volunteer_type   = 2;
        $rsp->save();

        $httpResponse = $this->call('POST', '/apply/'.$rsp->id, ['nav'=>'finalize'])
            ->assertStatus(302);

        $volunteer = $rsp->fresh()->respondent;

        $httpResponse->assertRedirect('/app-user/'.$volunteer->id.'/survey/priorities1/new');        
    }
    

    /**
     * @test
     */
    public function creates_a_new_volunteer_user_on_finalized_and_sets_as_respondent()
    {

        $rsp = $this->survey->getNewResponse(null);
        $rsp->applicant_name = 'billy pilgrim';
        $rsp->email = 'test@test.com';
        $rsp->volunteer_type   = 1;
        $rsp->finalize();


        $this->assertDatabaseHas('users', [
            'name' => 'billy pilgrim',
            'email' => 'test@test.com'
        ]);

        $user = User::where('email', 'test@test.com')->first();
        $this->assertEquals('App\User', $rsp->fresh()->respondent_type);
        $this->assertEquals($user->id, $rsp->fresh()->respondent_id);
    }
    
    
    
}
