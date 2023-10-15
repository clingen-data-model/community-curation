<?php

namespace Tests\Unit\Services\Search;

use App\Contracts\ModelSearchService;
use App\CurationActivity;
use App\CurationGroup;
use App\Services\Search\CurationGroupSearchService;
use App\WorkingGroup;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CurationGroupSearchServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function setup(): void
    {
        parent::setup();
        $this->service = new CurationGroupSearchService();
    }

    /**
     * @test
     */
    public function instantiates_a_search_service(): void
    {
        $this->assertInstanceOf(ModelSearchService::class, $this->service);
    }

    /**
     * @test
     */
    public function returns_all_groups_if_no_filters_given(): void
    {
        // Assumption: we have curation groups b/c of seeding
        $all = $this->service->search([]);
        $this->assertInstanceOf(Collection::class, $all);
        $this->assertEquals(CurationGroup::count(), $all->count());
    }

    /**
     * @test
     */
    public function filters_group_by_curation_activity_id(): void
    {
        $searchGroups = $this->service->search(['curation_activity_id' => config('project.curation-activities.gene')]);
        $testGroups = CurationGroup::where('curation_activity_id', config('project.curation-activities.gene'))->get();

        $this->assertEquals($testGroups->count(), $searchGroups->count());
        $this->assertEquals($testGroups->sortBy('id')->pluck('id'), $searchGroups->sortBy('id')->pluck('id'));
    }

    /**
     * @test
     */
    public function filters_group_by_working_group_id(): void
    {
        $searchGroups = $this->service->search(['working_group_id' => 1]);
        $testGroups = CurationGroup::where('working_group_id', 1)->get();

        $this->assertEquals($testGroups->count(), $searchGroups->count());
        $this->assertEquals($testGroups->sortBy('id')->pluck('id'), $searchGroups->sortBy('id')->pluck('id'));
    }

    /**
     * @test
     */
    public function filters_group_by_accepting_volunteers(): void
    {
        $searchGroups = $this->service->search(['accepting_volunteers' => 1]);
        $testGroups = CurationGroup::where('accepting_volunteers', 1)->get();

        $this->assertEquals($testGroups->count(), $searchGroups->count());
        $this->assertEquals($testGroups->sortBy('id')->pluck('id'), $searchGroups->sortBy('id')->pluck('id'));
    }

    /**
     * @test
     */
    public function filters_based_on_search_term(): void
    {
        $wg = factory(WorkingGroup::class)->create(['name' => 'monkeys']);
        $ca = factory(CurationActivity::class)->create(['name' => 'Monkey']);
        CurationGroup::factory(2)->create(['working_group_id' => $wg->id]);
        CurationGroup::factory()->create(['curation_activity_id' => $ca->id]);
        CurationGroup::factory()->create(['name' => 'monkey vcep']);

        $searchGroups = $this->service->search(['searchTerm' => 'monkey']);

        $this->assertEquals(4, $searchGroups->count());
    }
}
