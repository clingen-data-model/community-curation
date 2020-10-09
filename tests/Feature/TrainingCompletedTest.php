<?php

namespace Tests\Feature;

use App\Attestation;
use App\CurationActivity;
use App\Jobs\AssignVolunteerToAssignable;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @group training
 */
class TrainingCompletedTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->volunteer = factory(User::class)->states('volunteer', 'comprehensive')->create();
        AssignVolunteerToAssignable::dispatch($this->volunteer, CurationActivity::find(1));
    }

    /**
     * @test
     */
    public function attestation_created_for_training_when_completed_and_related_to_user_aptitude()
    {
        $training = $this->volunteer->userAptitudes()->first();

        $training->update(['trained_at' => '2019-11-01']);

        $attestationData = [
            'user_id' => $this->volunteer->id,
            'aptitude_id' => $training->aptitude_id,
            'assignment_id' => $training->assignment_id,
        ];

        $this->assertDatabaseHas('attestations', $attestationData);

        $attestation = Attestation::where($attestationData)->first();

        $this->assertDatabaseHas('user_aptitudes', [
            'user_id' => $this->volunteer->id,
            'aptitude_id' => $training->aptitude_id,
            'assignment_id' => $training->assignment_id,
            'attestation_id' => $attestation->id,
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
            'volunteer_status_id' => config('volunteers.statuses.trained'),
        ]);
    }
}
