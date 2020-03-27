<?php

namespace Tests\Feature;

use App\Gene;
use App\User;
use App\Assignment;
use Tests\TestCase;
use App\ExpertPanel;
use App\CurationActivity;
use App\Events\AssignmentCreated;
use Illuminate\Support\Facades\Event;
use App\Jobs\AssignVolunteerToAssignable;
use Illuminate\Foundation\Testing\WithFaker;
use App\Exceptions\InvalidAssignmentException;
use App\Events\Assignments\GroupAssignmentCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group assignments
 */
class AssignmentTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function baseline_volunteers_cannot_be_assigned_to_a_CurationActivity()
    {
        $baselineVolunteer = factory(User::class)->states(['volunteer','baseline'])->create(['volunteer_type_id' => 1]);
        $this->curationActivity = CurationActivity::query()->first();

        $this->expectException(InvalidAssignmentException::class);

        AssignVolunteerToAssignable::dispatch($baselineVolunteer, $this->curationActivity);
    }

    /**
     * @test
     */
    public function comprehensive_volunteers_can_be_assigned_to_a_CurationActivity()
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
    public function comprehensive_volunteers_can_only_be_assigned_to_a_CurationActivity_once()
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
    public function assignmentCreated_event_dispatched_when_assignment_created()
    {
        $volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
        $curationActivity = CurationActivity::all()->first();

        Event::fake();
        AssignVolunteerToAssignable::dispatch($volunteer, $curationActivity);
        Event::assertDispatched(AssignmentCreated::class);
    }

    /**
     * @test
     * @group userAptitudes
     */
    public function can_get_related_userAptitudes()
    {
        $volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
        $curationActivity = CurationActivity::all()->first();
        AssignVolunteerToAssignable::dispatch($volunteer, $curationActivity);

        $this->assertEquals($volunteer->assignments->first()->userAptitudes->first()->id, $volunteer->userAptitudes->first()->id);
    }

    /**
     * @test
     */
    public function can_scope_model_query_to_only_genes()
    {
        $curationActivity = factory(CurationActivity::class)->create([]);
        $gene = factory(Gene::class)->create([]);
        $ep = factory(ExpertPanel::class)->create(['curation_activity_id' => $curationActivity->id]);

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
    public function volunteer_status_set_to_active_on_first_group_assignment()
    {
        $curationActivity = factory(CurationActivity::class)->create([]);
        $gene = factory(Gene::class)->create([]);
        $ep = factory(ExpertPanel::class)->create(['curation_activity_id' => $curationActivity->id]);

        $volunteer = factory(User::class)->create();
        AssignVolunteerToAssignable::dispatch($volunteer, $curationActivity);
        $this->assertNotEquals(config('volunteers.statuses.active'), $volunteer->fresh()->volunteer_status_id);


        AssignVolunteerToAssignable::dispatch($volunteer, $gene);

        $this->assertEquals(config('volunteers.statuses.active'), $volunteer->fresh()->volunteer_status_id);

        $volunteer2 = factory(User::class)->create();
        AssignVolunteerToAssignable::dispatch($volunteer2, $ep);

        $this->assertEquals(config('volunteers.statuses.active'), $volunteer2->fresh()->volunteer_status_id);
    }
}
