<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ApiControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function redirects_to_login_if_guest()
    {
        $this->call('GET', '/api/users')
            ->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function returns_404_if_model_not_found()
    {
        $this->actingAs($this->user, 'api');
        $this->call('GET', '/api/non-existant-model-class')
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function returns_404_if_model_hidden_from_api()
    {
        config(['api.hidden-models' => [\App\User::class]]);

        $this->actingAs($this->user, 'api');
        $this->call('GET', '/api/user')
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function returns_list_if_available_model_provided_without_id()
    {
        $this->actingAs($this->user, 'api');
        $this->call('GET', '/api/volunteer-types')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => 1,
                        'name' => 'baseline',
                    ],
                    [
                        'id' => 2,
                        'name' => 'comprehensive',
                    ],
                ],
            ]);
    }

    /**
     * @test
     */
    public function returns_item_if_model_and_id_given()
    {
        $this->actingAs($this->user, 'api');
        $this->call('GET', '/api/volunteer-types/1')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => 1,
                    'name' => 'baseline',
                ],
            ]);
    }
}
