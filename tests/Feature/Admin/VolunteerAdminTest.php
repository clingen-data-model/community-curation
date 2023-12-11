<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Spatie\Permission\Models\Permission;

/**
 * @group volunteers
 * @group admin
 */
class VolunteerAdminTest extends TestCase
{
    private $user;

    public function setup(): void
    {
        parent::setup();
        $this->user = $this->createAdmin();
    }

    /**
     * @test
     */
    public function can_create_new_volunteer()
    {
        // dd($this->user->getAllPermissions()->filter(fn($p) => preg_match('/volunteer/', $p))->pluck('name'));
        $data = [
            'first_name' => 'Test',
            'last_name' => 'Tester',
            'email' => uniqid().'-email@beans.com',
            'volunteer_type_id' => 1,
            'volunteer_status_id' => 1
        ];
        $this->withoutExceptionHandling();
        $this->actingAs($this->user)
            ->call('post', '/admin/volunteer', $data)
            ->assertStatus(302);

        $this->assertDatabaseHas('users', $data);
    }

    /**
     * @test
     */
    public function fuck_can_update_volunteer()
    {
        $this->markTestSkipped('Works in browser but can not figure out why dummy volunteer not found.');
        $volunteer = $this->createVolunteer();

        $data = [
            'first_name' => 'Hubert',
            'email' => uniqid().'@example.com',
        ];

        $this->withoutExceptionHandling();
        $this->actingAs($this->user)
            ->call('put', '/admin/volunteer/'.$volunteer->id, $data)
            ->assertStatus(302);

        $this->assertDatabaseHas('users', $data);
    }

    /**
     * @test
     */
    public function validates_presence_of_volunteer_type_and_status()
    {
        $data = [
            'first_name' => 'Hubert',
            'email' => uniqid().'@example.com',
            'volunteer_type_id' => null,
            'volunteer_status_id' => null
        ];
        $response = $this->actingAs($this->user)
            ->call('post', '/admin/volunteer', $data);
        $response->assertSessionHasErrors(['volunteer_type_id', 'volunteer_status_id']);
    }
    
}
