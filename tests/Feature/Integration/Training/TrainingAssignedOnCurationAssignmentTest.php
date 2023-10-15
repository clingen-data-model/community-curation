<?php

namespace Tests\Feature\Integration\Training;

use App\CurationActivity;
use App\Jobs\AssignVolunteerToAssignable;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @group training
 */
class TrainingAssignedOnCurationAssignmentTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
    }

    /**
     * @test
     */
    public function training_assigned_for_curationActivity_when_volunteer_assigned_to_curationActivity(): void
    {
        $curationActivity = CurationActivity::find(1);
        AssignVolunteerToAssignable::dispatch($this->volunteer, $curationActivity);

        $this->assertDatabaseHas('user_aptitudes', [
            'user_id' => $this->volunteer->id,
            'aptitude_id' => $curationActivity->aptitudes->first()->id,
            'assignment_id' => $this->volunteer->assignments()->first()->id,
            'trained_at' => null,
        ]);
    }
}
