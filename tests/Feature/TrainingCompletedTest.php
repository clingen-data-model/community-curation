<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\CurationActivity;
use App\Jobs\AssignVolunteerToAssignable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group training
 */
class TrainingCompletedTest extends TestCase
{
    use DatabaseTransactions;
    
    public function setUp():void
    {
        parent::setUp();
        $this->volunteer = factory(User::class)->states('volunteer', 'comprehensive')->create();
        AssignVolunteerToAssignable::dispatch($this->volunteer, CurationActivity::find(1));
    }

    /**
     * @test
     */
    public function attestation_created_for_training_when_completed()
    {
        $training = $this->volunteer->userAptitudes()->first();
        // dd($training);
        $training->update(['trained_at' => '2019-11-01']);

        $this->assertDatabaseHas('attestations', [
            'user_id' => $this->volunteer->id,
            'aptitude_id' => $training->aptitude_id,
            'assignment_id' => $training->assignment_id
        ]);
    }
    
    /**
     * @test
     */
    public function volunteer_updated_to_trained_when_first_training_complete()
    {
        $training = $this->volunteer->userAptitudes()->first();
        $training->update(['trained_at' => '2019-11-01']);

        $this->assertDatabaseHas('users', [
            'id' => $this->volunteer->id,
            'volunteer_status_id' => config('volunteers.statuses.trained')
        ]);
    }
}
