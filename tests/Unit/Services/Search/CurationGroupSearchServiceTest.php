<?php

namespace Tests\Unit\Services\Search;

use Tests\TestCase;
use App\ExpertPanel;
use App\WorkingGroup;
use App\CurationActivity;
use App\Contracts\ModelSearchService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Search\CurationGroupSearchService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CurationGroupSearchServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function setup():void
    {
        parent::setup();
        $this->service = new CurationGroupSearchService();
    }

    /**
     * @test
     */
    public function instantiates_a_search_service()
    {
        $this->assertInstanceOf(ModelSearchService::class, $this->service);
    }

    /**
     * @test
     */
    public function returns_all_groups_if_no_filters_given()
    {
        // Assumption: we have curation groups b/c of seeding
        $all = $this->service->search([]);
        $this->assertInstanceOf(Collection::class, $all);
        $this->assertEquals(ExpertPanel::count(), $all->count());
    }


    /**
     * @test
     */
    public function filters_group_by_curation_activity_id()
    {
        $searchGroups = $this->service->search(['curation_activity_id'=>config('project.curation-activities.gene')]);
        $testGroups = ExpertPanel::where('curation_activity_id', config('project.curation-activities.gene'))->get();

        $this->assertEquals($testGroups->count(), $searchGroups->count());
        $this->assertEquals($testGroups->sortBy('id')->pluck('id'), $searchGroups->sortBy('id')->pluck('id'));
    }

    /**
     * @test
     */
    public function filters_group_by_working_group_id()
    {
        $searchGroups = $this->service->search(['working_group_id'=>1]);
        $testGroups = ExpertPanel::where('working_group_id', 1)->get();

        $this->assertEquals($testGroups->count(), $searchGroups->count());
        $this->assertEquals($testGroups->sortBy('id')->pluck('id'), $searchGroups->sortBy('id')->pluck('id'));
    }

    /**
     * @test
     */
    public function filters_group_by_accepting_volunteers()
    {
        $searchGroups = $this->service->search(['accepting_volunteers'=>1]);
        $testGroups = ExpertPanel::where('accepting_volunteers', 1)->get();

        $this->assertEquals($testGroups->count(), $searchGroups->count());
        $this->assertEquals($testGroups->sortBy('id')->pluck('id'), $searchGroups->sortBy('id')->pluck('id'));
    }

    /**
     * @test
     */
    public function filters_based_on_search_term()
    {
        $wg = factory(WorkingGroup::class)->create(['name' => 'monkeys']);
        $ca = factory(CurationActivity::class)->create(['name' => 'Monkey']);
        factory(ExpertPanel::class, 2)->create(['working_group_id' => $wg->id]);
        factory(ExpertPanel::class)->create(['curation_activity_id' => $ca->id]);
        factory(ExpertPanel::class)->create(['name' => 'monkey vcep']);



        $searchGroups = $this->service->search(['searchTerm' => 'monkey']);

        $this->assertEquals(4, $searchGroups->count());
    }
}
