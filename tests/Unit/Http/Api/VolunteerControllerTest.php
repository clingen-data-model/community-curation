<?php

namespace Tests\Unit\Http\Api;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VolunteerControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        \DB::statement("SET FOREIGN_KEY_CHECKS = 0");
        \DB::table('users')->truncate();
        \DB::statement("SET FOREIGN_KEY_CHECKS = 1");
        $this->volunteers = factory(User::class, 5)->states('volunteer')->create();
    }

    public function guest_redirected_to_login()
    {
        $this->call('GET', '/api/volunteers')
            ->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function index_returns_list_of_all_volunteers()
    {
        $this->volunteers->map(function ($v) {
            return [
                'id' => $v->id,
                'volunteer_type' => $v->volunteerType->toArray()
            ];
        });

        $response = $this->actingAs(factory(User::class)->create(), 'api')
            ->call('GET', '/api/volunteers');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function index_lists_only_volunteers()
    {
        $coordinator = factory(User::class)->states('coordinator')->create();

        $response = $this->actingAs(factory(User::class)->create(), 'api')
            ->call('GET', '/api/volunteers');
            
        $response->assertDontSee('"id": '.$coordinator->id);
    }

    /**
     * @test
     */
    public function show_returns_single_volunteer()
    {
        $response = $this->actingAs(factory(User::class)->state('admin')->create(), 'api')
                        ->call('GET', '/api/volunteers/'.$this->volunteers->first()->id)
                        ;
        $response->assertJson(['data' => [
            'id' => $this->volunteers->first()->id,
            'name' => $this->volunteers->first()->name,
            'volunteer_type' => [
                'id' => $this->volunteers->first()->volunteerType->id,
                'name' => $this->volunteers->first()->volunteerType->name
            ],
            'volunteer_status' => [
                'id' => $this->volunteers->first()->volunteerStatus->id,
                'name' => $this->volunteers->first()->volunteerStatus->name
            ],
        ]]);
    }
}
