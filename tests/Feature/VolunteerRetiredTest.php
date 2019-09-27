<?php

namespace Tests\Feature;

use App\CurationActivity;
use App\User;
use Tests\TestCase;
use App\ExpertPanel;
use App\Jobs\AssignVolunteerToAssignable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group volunteer
 */
class VolunteerRetiredTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp():void
    {
        parent::setUp();
        $this->activity = CurationActivity::find(1);
        $this->expertPanel = factory(ExpertPanel::class)->create(['curation_activity_id' => $this->activity->id]);
        $this->volunteer = factory(User::class)->states('volunteer', 'comprehensive')->create();
        AssignVolunteerToAssignable::dispatch($this->volunteer, $this->activity);
        AssignVolunteerToAssignable::dispatch($this->volunteer, $this->expertPanel);
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
            'assignment_status_id' => config('project.assignment-statuses.retired')
        ]);
        $this->assertDatabaseHas('assignments', [
            'user_id' => $this->volunteer->id,
            'assignable_type' => get_class($this->expertPanel),
            'assignable_id' => $this->expertPanel->id,
            'assignment_status_id' => config('project.assignment-statuses.retired')
        ]);
    }
}
