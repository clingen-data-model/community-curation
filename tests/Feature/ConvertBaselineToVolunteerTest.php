<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class ConvertBaselineToVolunteerTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();
        $this->volunteer = factory(User::class)->states(['volunteer', 'baseline'])->create();
    }

    /**
     * @test
     */
    public function can_convert_to_comprehensive_volunteer(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin, 'api')
            ->json('PUT', '/api/volunteers/'.$this->volunteer->id, ['volunteer_type_id' => 2])
            ->assertStatus(200);
    }
}
