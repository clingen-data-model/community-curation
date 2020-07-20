<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationCompletedMail;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group application
 * @group surveys
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
    public function a_new_application_can_be_started_by_a_guest()
    {
        $this->withoutExceptionHandling();
        $this->call('GET', '/apply')
            ->assertStatus(200)
            ->assertSee('Introduction')
            ->assertSee('Thank you for your interest in volunteering as a curator for ClinGen.')
            ->assertSessionHas('application-response');

        $response = $this->call('POST', '/apply', [
            'nav' => 'next'
        ]);

        $appRsp = class_survey()::findBySlug('application1')->responses->last();

        $response->assertRedirect('/apply/'.$appRsp->id.'?page=demographic')
            // ->assertSee('Demographic Questions')
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

        Session::put('application-response', $rsp);

        $this->call('GET', 'apply/'.$rsp->id)
             ->assertStatus(200)
             ->assertSee('response_id: '.$rsp->id);
                
        $this->assertEquals(session()->get('application-response')->id, $rsp->id);
    }
    
    /**
     * @test
     */
    public function guest_cannot_view_or_update_response_that_is_not_in_their_session()
    {
        $rsp1 = $this->survey->getNewResponse(null);
        $rsp1->save();
        $rsp2 = $this->survey->getNewResponse(null);
        $rsp2->save();

        Session::put('application-response', $rsp1);

        $this->expectUnauthorized();
        $this->call('GET', 'apply/'.$rsp2->id);

        $this->expectUnauthorized();
        $this->call('POST', 'apply/'.$rsp2->id, ['nav' => 'next']);
    }
    
    /**
     * @test
     */
    public function user_cannot_access_response_of_another_respondent()
    {
        $u1 = $this->createVolunteer();
        $u2 = $this->createVolunteer();

        $rsp1 = $this->survey->getNewResponse(null);
        $rsp1->save();
        $rsp2 = $this->survey->getNewResponse($u1);
        $rsp2->save();

        $anotherUser = $u2->fresh();
        
        $this->expectUnauthorized();
        $this->actingAs($u2)
            ->call('GET', 'apply/'.$rsp1->id);

        $this->expectUnauthorized();
        $this->actingAs($u2)
            ->call('GET', 'apply/'.$rsp2->id);
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
        $rsp->orcid_id = '123';
        $rsp->volunteer_type   = 1;
        $rsp->institution = 'Monkey Biz U';
        $rsp->street1 = '123 test street';
        $rsp->street2 = 'Apt test';
        $rsp->city = 'Testville';
        $rsp->state = 'Ca';
        $rsp->country_id = 225;
        $rsp->finalize();


        $this->assertDatabaseHas('users', [
            'first_name' => 'billy',
            'last_name' => 'pilgrim',
            'email' => 'test@test.com',
            'orcid_id' => '123',
            'institution' => 'Monkey Biz U'
        ]);

        $user = User::where('email', 'test@test.com')->first();
        $this->assertEquals('App\User', $rsp->fresh()->respondent_type);
        $this->assertEquals($user->id, $rsp->fresh()->respondent_id);
        $this->assertEquals($user->orcid_id, $rsp->fresh()->orcid_id);
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
    public function stores_Hypothesis_username_if_entered()
    {
        $rsp = $this->survey->getNewResponse(null);
        $rsp->first_name = 'billy';
        $rsp->last_name = 'pilgrim';
        $rsp->email = 'test@test.com';
        $rsp->orcid_id = '123';
        $rsp->volunteer_type   = 1;
        $rsp->institution = 'Monkey Biz U';
        $rsp->street1 = '123 test street';
        $rsp->street2 = 'Apt test';
        $rsp->city = 'Testville';
        $rsp->state = 'Ca';
        $rsp->country_id = 225;
        $rsp->hypothesis_id = 'beans';
        $rsp->finalize();


        $this->assertDatabaseHas('users', [
            'first_name' => 'billy',
            'last_name' => 'pilgrim',
            'email' => 'test@test.com',
            'orcid_id' => '123',
            'institution' => 'Monkey Biz U',
            'hypothesis_id' => 'beans'
        ]);
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

        Session::put('application-response', $rsp);

        $this->call('POST', '/apply/'.$rsp->id, ['nav'=>'finalize'])
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

        Session::put('application-response', $rsp);

        $this->withoutExceptionHandling();
        $this->call('POST', '/apply/'.$rsp->id, ['nav'=>'finalize'])
            ->assertStatus(302);

        $this->assertDatabaseHas('priorities', [
            'curation_activity_id' => 1,
            'curation_group_id' => 1,
            'activity_experience' => 1,
            'activity_experience_details' => 'test details'
        ]);
        $this->assertDatabaseHas('priorities', [
            'curation_activity_id' => 2,
            'curation_group_id' => 2,
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
        
        Mail::fake();
        $rsp->finalize();

        Mail::assertSent(ApplicationCompletedMail::class, function ($mail) use ($rsp) {
            return $mail->hasTo($rsp->email);
        });
    }
}
