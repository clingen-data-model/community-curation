<?php

namespace Tests\Unit\Models;

use App\User;
use Tests\TestCase;
use App\CurationActivity;
use App\Jobs\AssignVolunteerToAssignable;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group training
 */
class UserTrainingTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * @test
     */
    public function training_can_get_its_related_assignment()
    {
        $vol = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
        AssignVolunteerToAssignable::dispatch($vol, CurationActivity::find(1));

        $assignment = $vol->assignments->first();
        $training = $vol->trainings->first();

        $this->assertEquals($assignment->id, $training->assignment->id);
    }
    
}
