<?php

namespace Tests\Feature;

use App\Assignment;
use App\CurationActivity;
use App\CurationGroup;
use App\Events\AssignmentCreated;
use App\Exceptions\InvalidAssignmentException;
use App\Gene;
use App\Jobs\AssignVolunteerToAssignable;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * @group assignments
 */
class AssignmentTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function baseline_volunteers_cannot_be_assigned_to_a_CurationActivity(): void
    {
        $baselineVolunteer = factory(User::class)->states(['volunteer', 'baseline'])->create(['volunteer_type_id' => 1]);
        $this->curationActivity = CurationActivity::query()->first();

        $this->expectException(InvalidAssignmentException::class);

        AssignVolunteerToAssignable::dispatch($baselineVolunteer, $this->curationActivity);
    }

    /**
     * @test
     */
    public function comprehensive_volunteers_can_be_assigned_to_a_CurationActivity(): void
    {
        $volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
        $curationActivity = CurationActivity::all()->random();

        AssignVolunteerToAssignable::dispatch($volunteer, $curationActivity);

        $this->assertEquals(1, $volunteer->fresh()->assignments->count());
        $this->assertEquals($curationActivity->id, $volunteer->fresh()->assignments->first()->assignable_id);
    }

    /**
     * @test
     */
    public function comprehensive_volunteers_can_only_be_assigned_to_a_CurationActivity_once(): void
    {
        $volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
        $curationActivity = CurationActivity::all()->first();

        AssignVolunteerToAssignable::dispatch($volunteer, $curationActivity);
        AssignVolunteerToAssignable::dispatch($volunteer, $curationActivity);

        $this->assertEquals(1, $volunteer->fresh()->assignments->count());
    }

    /**
     * @test
     */
    public function curation_groups_can_be_assigned_without_a_parent_assignment(): void
    {
        $volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
        $curationGroup = CurationGroup::all()->first();

        AssignVolunteerToAssignable::dispatch($volunteer, $curationGroup);

        $this->assertEquals(1, $volunteer->fresh()->assignments->count());
    }

    /**
     * @test
     */
    public function curation_group_assignment_has_ca_assignment_as_parent_if_exists(): void
    {
        $volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
        $curationActivity = CurationActivity::all()->first();
        $curationGroup = $curationActivity->curationGroups->random();

        AssignVolunteerToAssignable::dispatch($volunteer, $curationActivity);
        AssignVolunteerToAssignable::dispatch($volunteer, $curationGroup);

        $assignments = $volunteer->assignments;

        $this->assertEquals(2, $volunteer->fresh()->assignments->count());

        $this->assertEquals(
            $assignments->isCurationActivity()->first()->id,
            $volunteer->assignments()->curationGroup()->first()->parent_id
        );
    }

    /**
     * @test
     */
    public function gene_assignment_has_a_baseline_assignment_parent_if_exists(): void
    {
        $volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
        $curationActivity = CurationActivity::where('name', 'Baseline')->first();
        $gene = factory(Gene::class)->create();

        AssignVolunteerToAssignable::dispatch($volunteer, $curationActivity);
        AssignVolunteerToAssignable::dispatch($volunteer, $gene);

        $assignments = $volunteer->assignments;

        $this->assertEquals(2, $volunteer->fresh()->assignments->count());

        $this->assertEquals(
            $assignments->isCurationActivity()->first()->id,
            $volunteer->assignments()->gene()->first()->parent_id
        );
    }

    /**
     * @test
     */
    public function assignmentCreated_event_dispatched_when_assignment_created(): void
    {
        $volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
        $curationActivity = CurationActivity::all()->first();

        Event::fake();
        AssignVolunteerToAssignable::dispatch($volunteer, $curationActivity);
        Event::assertDispatched(AssignmentCreated::class);
    }

    /**
     * @test
     *
     * @group userAptitudes
     */
    public function can_get_related_userAptitudes(): void
    {
        $volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
        $curationActivity = CurationActivity::all()->first();
        AssignVolunteerToAssignable::dispatch($volunteer, $curationActivity);

        $this->assertEquals($volunteer->fresh()->assignments->first()->userAptitudes->first()->id, $volunteer->userAptitudes->first()->id);
    }

    /**
     * @test
     */
    public function can_scope_model_query_to_only_genes(): void
    {
        $curationActivity = factory(CurationActivity::class)->create([]);
        $gene = factory(Gene::class)->create([]);
        $ep = CurationGroup::factory()->create(['curation_activity_id' => $curationActivity->id]);

        $volunteer = factory(User::class)->create();
        AssignVolunteerToAssignable::dispatch($volunteer, $curationActivity);
        AssignVolunteerToAssignable::dispatch($volunteer, $ep);
        AssignVolunteerToAssignable::dispatch($volunteer, $gene);

        $this->assertEquals($gene->id, Assignment::gene()->first()->assignable_id);
        $this->assertEquals(Gene::class, Assignment::gene()->first()->assignable_type);
    }

    /**
     * @test
     */
    public function volunteer_status_set_to_active_on_first_group_assignment(): void
    {
        $curationActivity = factory(CurationActivity::class)->create([]);
        $gene = factory(Gene::class)->create([]);
        $ep = CurationGroup::factory()->create(['curation_activity_id' => $curationActivity->id]);

        $volunteer = factory(User::class)->create();
        AssignVolunteerToAssignable::dispatch($volunteer, $curationActivity);
        $this->assertNotEquals(config('volunteers.statuses.active'), $volunteer->fresh()->volunteer_status_id);

        AssignVolunteerToAssignable::dispatch($volunteer, $gene);

        $this->assertEquals(config('volunteers.statuses.active'), $volunteer->fresh()->volunteer_status_id);

        $volunteer2 = factory(User::class)->create();
        AssignVolunteerToAssignable::dispatch($volunteer2, $ep);

        $this->assertEquals(config('volunteers.statuses.active'), $volunteer2->fresh()->volunteer_status_id);
    }

    /**
     * @test
     */
    public function deletes_user_aptitudes_related_to_assignment_when_deleting(): void
    {
        Carbon::setTestNow('2020-01-01 01:01:01');
        $ca = CurationActivity::query()
            ->geneType()
            ->get()->random();
        $apt = $ca->getPrimaryAptitude();
        $volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
        AssignVolunteerToAssignable::dispatchSync($volunteer, $ca);

        $aptitudes = $volunteer->fresh()
            ->userAptitudes()
            ->where('aptitude_id', $apt->id)
            ->get();

        $this->assertEquals(1, $aptitudes->count());

        $assignment = $volunteer->assignments()->first();
        $assignment->delete();

        $this->assertDatabaseHas('user_aptitudes', [
            'user_id' => $volunteer->id,
            'aptitude_id' => $apt->id,
            'deleted_at' => '2020-01-01 01:01:01',
        ]);
    }
}
