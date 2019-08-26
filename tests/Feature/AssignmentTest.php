<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\CurationActivity;
use InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use App\Jobs\AssignVolunteerToCurationActivity;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

        AssignVolunteerToCurationActivity::dispatch($baselineVolunteer, $curationActivity);
    }

    /**
     * @test
     */
    public function comprehensive_volunteers_can_be_assigned_to_a_CurationActivity()
    {
        $volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
        $curationActivity = CurationActivity::all()->random();

        AssignVolunteerToCurationActivity::dispatch($volunteer, $curationActivity);

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

        AssignVolunteerToCurationActivity::dispatch($volunteer, $curationActivity);
        AssignVolunteerToCurationActivity::dispatch($volunteer, $curationActivity);
        
        $this->assertEquals(1, $volunteer->fresh()->assignments->count());
    }
    
}
