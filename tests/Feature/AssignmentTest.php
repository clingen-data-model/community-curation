<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\CurationActivity;
use InvalidArgumentException;
use App\Events\AssignmentCreated;
use App\Jobs\AssignVolunteerToAssignable;
use Illuminate\Foundation\Testing\WithFaker;
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
        $curationActivity = CurationActivity::all()->random();

        $this->expectException(InvalidArgumentException::class);

        AssignVolunteerToAssignable::dispatch($baselineVolunteer, $curationActivity);
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
        $curationActivity2 = CurationActivity::all()->last();

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

        \Event::fake();
        AssignVolunteerToAssignable::dispatch($volunteer, $curationActivity);
        \Event::assertDispatched(AssignmentCreated::class);
    }

    /**
     * @test
     * @group training
     */
    public function can_get_related_trainings()
    {
        $volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
        $curationActivity = CurationActivity::all()->first();
        AssignVolunteerToAssignable::dispatch($volunteer, $curationActivity);

        dump($volunteer->assignments->first()->trainings->first()->id);

        $this->assertEquals($volunteer->assignments->first()->trainings->first()->id, $volunteer->trainings->first()->id);
    }
    
    
}
