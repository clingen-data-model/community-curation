<?php

namespace Tests\Unit;

use App\Note;
use App\User;
use Tests\TestCase;
use App\CurationGroup;
use App\Services\Search\NotesSearchService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group notes
 */
class NotesControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setup():void
    {
        parent::setup();
        $this->notes = factory(Note::class, 2)->create();
        $this->user = factory(User::class)->states(['admin'])->create([]);
        $this->notes->merge(factory(Note::class, 2)->create(['notable_type'=>User::class, 'notable_id' => $this->user->id]));
        $this->service = app()->make(NotesSearchService::class);
    }

    /**
     * @test
     */
    public function index_returns_all_notes_if_no_params()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/notes')
            ->assertStatus(200);

        $this->assertEquals(4, $response->original->count());
    }

    /**
     * @test
     */
    public function index_returns_filtered_notes_if_params_given()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/notes?notable_type=App\\User&notable_id='.$this->user->id);

        $this->assertEquals(2, $response->original->count());
        $this->assertEquals($this->user->id, $response->original->first()->notable_id);
    }

    /**
     * @test
     */
    public function admin_can_store_a_new_note()
    {
        $this->withoutExceptionHandling();
        $user = $this->createAdmin();
        $curationGroup = factory(CurationGroup::class)->create([]);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', 'api/notes', [
                'notable_type' => CurationGroup::class,
                'notable_id' => $curationGroup->id,
                'content' => 'This is a test.'
            ])
            ->assertStatus(201)
            ->assertJson(['data' => [
                'notable_type' => 'App\\CurationGroup',
                'notable_id' => $curationGroup->id,
                'content' => 'This is a test.'
            ]]);

        $this->assertDatabaseHas('notes', [
            'notable_type' => CurationGroup::class,
            'notable_id' => $curationGroup->id,
            'content' => 'This is a test.'
        ]);
    }

    /**
     * @test
     */
    public function validates_for_required_notable_content_before_store()
    {
        $user = $this->createAdmin();
        $curationGroup = factory(CurationGroup::class)->create([]);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', 'api/notes', [
            ])
            ->assertStatus(422)
            ->assertJson(['errors' => [
                'notable_type' => ['The notable type field is required.'],
                'notable_id' => ['The notable id field is required.'],
                'content' => ['The content field is required.']
            ]]);
    }

    /**
     * @test
     */
    public function gets_an_existing_note()
    {
        $use = $this->createAdmin();
        $curationGroup = factory(CurationGroup::class)->create([]);

        $note = factory(Note::class)->create(['notable_type' => CurationGroup::class, 'notable_id' => $curationGroup->id]);

        $this->actingAs($this->user, 'api')
            ->json('get', 'api/notes/'.$note->id)
            ->assertStatus(200)
            ->assertJson(['data' => ['content' => $note->content]]);
    }
}
