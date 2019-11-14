<?php

namespace Tests\Unit\Models;

use App\CurationActivity;
use App\Jobs\AssignVolunteerToAssignable;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group training
 */
class UserTrainingTest extends TestCase
{
    /**
     * @test
     */
    public function training_can_get_its_related_assignment()
    {
        $vol = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
        AssignVolunteerToAssignable::dispatch($vol, CurationActivity::find(1));

        $assignment = $vol->assignments->first();
        $training = $vol->trainings->first();

        $this->assertEquals($assignment->id, $training->getAssignment()->id);
    }
    
}
