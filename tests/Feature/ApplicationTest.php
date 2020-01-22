<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Mail\ApplicationCompletedMail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group application
 */
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
    public function creates_a_new_volunteer_user_on_finalized_and_sets_as_respondent()
    {
        $rsp = $this->survey->getNewResponse(null);
        $rsp->first_name = 'billy';
        $rsp->last_name = 'pilgrim';
        $rsp->email = 'test@test.com';
        $rsp->volunteer_type   = 1;
        $rsp->street1 = '123 test street';
        $rsp->street2 = 'Apt test';
        $rsp->city = 'Testville';
        $rsp->state = 'Ca';
        $rsp->country_id = 225;
        $rsp->finalize();


        $this->assertDatabaseHas('users', [
            'first_name' => 'billy',
            'last_name' => 'pilgrim',
            'email' => 'test@test.com'
        ]);

        $user = User::where('email', 'test@test.com')->first();
        $this->assertEquals('App\User', $rsp->fresh()->respondent_type);
        $this->assertEquals($user->id, $rsp->fresh()->respondent_id);
        $this->assertEquals($user->street1, $rsp->street1);
        $this->assertEquals($user->street2, $rsp->street2);
        $this->assertEquals($user->city, $rsp->city);
        $this->assertEquals($user->state, $rsp->state);
        $this->assertEquals($user->zip, $rsp->zip);
        $this->assertEquals($user->country_id, $rsp->country_id);
    }
    
    /**
     * @test
     */
    public function removes_response_from_session_if_baseline()
    {
        $rsp = $this->survey->getNewResponse(null);
        $rsp->first_name = 'billy';
        $rsp->last_name = 'pilgrim';
        $rsp->email = 'test@test.com';
        $rsp->volunteer_type   = 1;
        $rsp->finalize();

        $this->assertNUll(session()->get('application-response'));
        $this->assertFalse(session()->has('application-response'));
    }

    /**
     * @test
     */
    public function redirects_to_thank_you_if_baseline()
    {
        $rsp = $this->survey->getNewResponse(null);
        $rsp->first_name = 'billy';
        $rsp->last_name = 'pilgrim';
        $rsp->email = 'test@test.com';
        $rsp->volunteer_type   = 1;
        $rsp->save();

        $httpResponse = $this->call('POST', '/apply/'.$rsp->id, ['nav'=>'finalize'])
            ->assertRedirect('apply/thank-you');
    }

    /**
     * @test
     */
    public function stores_priorities()
    {
        $rsp = $this->survey->getNewResponse(null);
        $rsp->first_name = 'billy';
        $rsp->last_name = 'pilgrim';
        $rsp->email = 'test@test.com';
        $rsp->volunteer_type   = 2;
        $rsp->curation_activity_1 = 1;
        $rsp->panel_1 = 1;
        $rsp->activity_experience_1 = 1;
        $rsp->activity_experience_1_detail = 'test details';
        $rsp->curation_activity_2 = 2;
        $rsp->panel_2 = 2;
        $rsp->effort_experience_2 = 1;
        $rsp->effort_experience_2_detail = 'test effort experience details 2';
        $rsp->save();

        $this->withoutExceptionHandling();
        $this->call('POST', '/apply/'.$rsp->id, ['nav'=>'finalize'])
            ->assertStatus(302);

        $this->assertDatabaseHas('priorities', [
            'curation_activity_id' => 1,
            'expert_panel_id' => 1,
            'activity_experience' => 1,
            'activity_experience_details' => 'test details'
        ]);
        $this->assertDatabaseHas('priorities', [
            'curation_activity_id' => 2,
            'expert_panel_id' => 2,
            'activity_experience' => 0,
            'activity_experience_details' => null,
            'effort_experience' => 1,
            'effort_experience_details' => 'test effort experience details 2'
        ]);
    }
    
    /**
     * @test
     */
    public function system_sends_volunteer_an_email_when_they_completed_their_application()
    {
        $rsp = $this->survey->getNewResponse(null);
        $rsp->first_name = 'billy';
        $rsp->last_name = 'pilgrim';
        $rsp->email = 'test@test.com';
        $rsp->save();

        $mail = new ApplicationCompletedMail($rsp);
        $this->assertContains('Dear billy pilgrim,', $mail->render());
        
        \Mail::fake();
        $rsp->finalize();

        \Mail::assertSent(ApplicationCompletedMail::class, function ($mail) use ($rsp) {
            return $mail->hasTo($rsp->email);
        });

    }
}
