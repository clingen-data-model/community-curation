<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApplicationTest extends TestCase
{
    // use DatabaseTransactions;

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
    
    
    
    
}
