<?php

namespace Tests\Unit\Http\Api;

use Tests\TestCase;

class VolunteerMetricsControllerTest extends TestCase
{
    private $admin;

    public function setup(): void
    {
        parent::setup();
        $this->admin = $this->createAdmin();
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_reach_the_metrics_controller()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->admin, 'api')
            ->json('GET', '/api/volunteers/metrics')
            ->assertStatus(200);
    }
}
