<?php

namespace Tests\Feature;

use App\CurationActivity;
use App\CurationGroup;
use App\Jobs\AssignVolunteerToAssignable;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @group volunteer
 */
class VolunteerRetiredTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->activity = CurationActivity::find(1);
        $this->curationGroup = CurationGroup::factory()->create(['curation_activity_id' => $this->activity->id]);
        $this->volunteer = factory(User::class)->states('volunteer', 'comprehensive')->create();
        AssignVolunteerToAssignable::dispatch($this->volunteer, $this->activity);
        AssignVolunteerToAssignable::dispatch($this->volunteer, $this->curationGroup);
    }

    /**
     * @test
     */
    public function assignment_statuses_set_to_retired_when_volunteer_status_set_to_retired()
    {
        $this->volunteer->update(['volunteer_status_id' => config('volunteers.statuses.retired')]);

        $this->assertDatabaseHas('assignments', [
            'user_id' => $this->volunteer->id,
            'assignable_type' => get_class($this->activity),
            'assignable_id' => $this->activity->id,
            'assignment_status_id' => config('project.assignment-statuses.retired'),
        ]);
        $this->assertDatabaseHas('assignments', [
            'user_id' => $this->volunteer->id,
            'assignable_type' => get_class($this->curationGroup),
            'assignable_id' => $this->curationGroup->id,
            'assignment_status_id' => config('project.assignment-statuses.retired'),
        ]);
    }

    /**
     * @test
     */
    public function assignment_statuses_set_to_retired_when_volunteer_status_set_to_declined()
    {
        $this->volunteer->update(['volunteer_status_id' => config('volunteers.statuses.declined')]);

        $this->assertDatabaseHas('assignments', [
            'user_id' => $this->volunteer->id,
            'assignable_type' => get_class($this->activity),
            'assignable_id' => $this->activity->id,
            'assignment_status_id' => config('project.assignment-statuses.retired'),
        ]);
        $this->assertDatabaseHas('assignments', [
            'user_id' => $this->volunteer->id,
            'assignable_type' => get_class($this->curationGroup),
            'assignable_id' => $this->curationGroup->id,
            'assignment_status_id' => config('project.assignment-statuses.retired'),
        ]);
    }

    /**
     * @test
     */
    public function assignment_statuses_set_to_retired_when_volunteer_status_set_to_unresponsive()
    {
        $this->volunteer->update(['volunteer_status_id' => config('volunteers.statuses.unresponsive')]);

        $this->assertDatabaseHas('assignments', [
            'user_id' => $this->volunteer->id,
            'assignable_type' => get_class($this->activity),
            'assignable_id' => $this->activity->id,
            'assignment_status_id' => config('project.assignment-statuses.retired'),
        ]);
        $this->assertDatabaseHas('assignments', [
            'user_id' => $this->volunteer->id,
            'assignable_type' => get_class($this->curationGroup),
            'assignable_id' => $this->curationGroup->id,
            'assignment_status_id' => config('project.assignment-statuses.retired'),
        ]);
    }
}
