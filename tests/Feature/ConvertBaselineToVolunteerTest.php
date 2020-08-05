<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConvertBaselineToVolunteerTest extends TestCase
{
    public function setup():void
    {
        parent::setup();
        $this->volunteer = factory(User::class)->states(['volunteer', 'baseline'])->create();
    }

    /**
     * @test
     */
    public function can_convert_to_comprehensive_volunteer()
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin, 'api')
            ->call('PUT', '/api/volunteers/'.$this->volunteer->id, ['volunteer_type_id' => 2])
            ->assertStatus(200);
    }
    
    
}
