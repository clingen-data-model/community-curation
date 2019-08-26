<?php

namespace Tests\Unit\Controllers\Api;

use App\CurationActivity;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group assignments
 */

class AssignmentControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp():void
    {
        parent::setUp();
        $this->user = factory(User::class)->state('admin')->create();
        $this->volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
    }

    /**
     * @test
     */
    public function store_validates_request()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/assignments', []);
        $response->assertStatus(422);
        $response->assertSee('The assignable id field is required');
        $response->assertSee('The user id field is required');
        $response->assertSee('The assignable type field is required');

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/assignments', [
                'assignable_type' => 'test',
                'assignable_id' => 1,
                'user_id' => 1000
            ]);
        $response->assertSee('The selected assignable type is invalid.');
        $response->assertSee('The selected user id is invalid.');
        $response->assertDontSee('The selected assignable id field is required.');

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/assignments', [
                'assignable_type' => CurationActivity::class,
                'assignable_id' => 1,
                'user_id' => $this->volunteer->id
            ]);
    }

    /**
     * @test
     */
    public function stores_new_assignment()
    {
        $response = $this->actingAs($this->user, 'api')
            ->withoutExceptionHandling()
            ->json('POST', '/api/assignments', [
                'assignable_type' => CurationActivity::class,
                'assignable_id' => 1,
                'user_id' => $this->volunteer->id
            ]);
        $response->assertStatus(200);

        $response->assertSee($this->volunteer->email);
        $response->assertSee($this->volunteer->name);
        $response->assertSee(CurationActivity::find(1)->name);

        $this->assertDatabaseHas('assignments', [
            'assignable_type' => CurationActivity::class,
            'assignable_id' => 1,
            'user_id' => $this->volunteer->id
        ]);
        
        $this->assertEquals(1, CurationActivity::find(1)->assignments->count());

    }
    
}
