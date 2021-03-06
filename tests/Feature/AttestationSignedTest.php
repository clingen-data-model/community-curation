<?php

namespace Tests\Feature;

use App\CurationActivity;
use App\Jobs\AssignVolunteerToAssignable;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @group attestations
 * @group training
 */
class AttestationSignedTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->volunteer = factory(User::class)->states('volunteer', 'comprehensive')->create();
        AssignVolunteerToAssignable::dispatch($this->volunteer, CurationActivity::find(config('project.curation-activities.baseline')));
        $this->userAptitude = $this->volunteer->userAptitudes()->first();
        $this->userAptitude->update(['trained_at' => '2019-11-01']);
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
            'assignable_id' => config('project.curation-activities.baseline'),
            'user_id' => $this->volunteer->id,
            'assignment_status_id' => config('project.assignment-statuses.trained'),
        ]);
    }

    /**
     * @test
     */
    public function user_aptitude_marked_granted_when_attestation_signed()
    {
        $this->attestation->update(['signed_at' => Carbon::now()]);

        $this->assertNotNull($this->userAptitude->fresh()->granted_at);
    }

    /**
     * @test
     */
    public function hypothesis_id_added_to_user_when_attestation_signed()
    {
        $this->attestation->update(['signed_at' => Carbon::now(), 'data' => ['hypothesis_id' => 'porkypiney']]);

        $this->assertDatabaseHas('users', [
            'id' => $this->attestation->user_id,
            'hypothesis_id' => 'porkypiney',
        ]);
    }
}
