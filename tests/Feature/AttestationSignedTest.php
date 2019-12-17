<?php

namespace Tests\Feature;

use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use App\CurationActivity;
use App\Jobs\AssignVolunteerToAssignable;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group attestations
 * @group training
 */
class AttestationSignedTest extends TestCase
{
    use DatabaseTransactions;
    
    public function setUp():void
    {
        parent::setUp();
        $this->volunteer = factory(User::class)->states('volunteer', 'comprehensive')->create();
        AssignVolunteerToAssignable::dispatch($this->volunteer, CurationActivity::find(1));
        $training = $this->volunteer->trainings()->first();
        $training->update(['completed_at' => '2019-11-01']);
        $this->attestation = $this->volunteer->attestations()->unsigned()->first();
    }

    /**
     * @test
     */
    public function assignment_status_set_to_trained_when_attestation_signed()
    {
        $this->attestation->update(['signed_at' => Carbon::now()]);

        $this->assertDatabaseHas('assignments', [
            'assignable_type' => 'App\CurationActivity',
            'assignable_id' => 1,
            'user_id' => $this->volunteer->id,
            'assignment_status_id' => config('project.assignment-statuses.trained')
        ]);
    }
    


}
