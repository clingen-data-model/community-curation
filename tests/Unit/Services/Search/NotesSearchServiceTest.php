<?php

namespace Tests\Unit\Services\Search;

use App\Note;
use App\User;
use Tests\TestCase;
use App\Services\Search\NotesSearchService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group notes
 */
class NotesSearchServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function setup():void
    {
        parent::setup();
        $this->service = new NotesSearchService();
    }
    

    /**
     * @test
     */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(NotesSearchService::class, $this->service);
    }

    /**
     * @test
     */
    public function it_returns_all_notes_when_no_parameters_given()
    {
        $notes = factory(Note::class, 3)->create([
            'notable_type' => CurationGroup::class,
        ]);

        $this->assertEquals(3, $this->service->search([])->count());
    }

    /**
     * @test
     */
    public function it_can_filter_results_by_notable_type()
    {
        $note = factory(Note::class)->create([
            'notable_type' => CurationGroup::class,
        ]);
        
        $note = factory(Note::class)->create([
            'notable_type' => User::class,
        ]);

        $this->assertEquals(1, $this->service->search(['notable_type' => User::class])->count());
    }

    /**
     * @test
     */
    public function it_can_filter_by_notable_type_and_id()
    {
        factory(Note::class)->create([
            'notable_type' => CurationGroup::class,
        ]);
        
        factory(Note::class)->create([
            'notable_type' => User::class,
        ]);

        $user = factory(User::class)->create();

        $note = factory(Note::class)->create(['notable_type' => User::class, 'notable_id' => $user->id]);

        $results = $this->service->search(['notable_type' => User::class, 'notable_id' => $user->id]);
        $this->assertEquals(1, $results->count());
        $this->assertEquals($note->id, $results->first()->id);
    }

    /**
     * @test
     */
    public function filtering_by_content_does_like_search()
    {
        $otherNotes = factory(Note::class, 3)->create([]);
        $note = factory(Note::class)->create(['content' => 'this is a test of cucumbers and cats']);
        $results = $this->service->search(['content' => 'cucumbers and cats']);

        $this->assertEquals(1, $results->count());
        $this->assertContains($note->id, $results->pluck('id'));
    }
}
