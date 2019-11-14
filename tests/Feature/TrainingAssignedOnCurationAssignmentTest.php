<?php

namespace Tests\Feature;

use App\User;
use App\Training;
use Tests\TestCase;
use App\CurationActivity;
use App\Jobs\AssignVolunteerToAssignable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group training
 */
class TrainingAssignedOnCurationAssignmentTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp():void
    {
        parent::setUp();
        $this->volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
    }

    /**
     * @test
     */
    public function training_assigned_for_curationActivity_when_volunteer_assigned_to_curationActivity()
    {
        $curationActivity = CurationActivity::find(1);
        AssignVolunteerToAssignable::dispatch($this->volunteer, $curationActivity);

        $this->assertDatabaseHas('training_user', [
            'user_id' => $this->volunteer->id,
            'training_id' => $curationActivity->trainings->first()->id,
            'completed_at' => null,
        ]);
    }
    
    
}
