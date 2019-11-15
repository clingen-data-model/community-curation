<?php

namespace Tests\Unit\Models;

use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use App\UserTraining;
use App\CurationActivity;
use App\Events\UserTrainingCompleted;
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
        $userTraining = $vol->userTrainings->first();

        $this->assertEquals($assignment->id, $userTraining->assignment->id);
    }

    /**
     * @test
     */
    public function disptatches_UserTrainingCompletedEvent_when_completed_at_changed_from_null()
    {
        $vol = factory(User::class)->states(['volunteer', 'baseline'])->create();
        $userTraining = $vol->userTrainings()
                            ->create([
                                'training_id' => 2,
                            ]);

        $listenerCalled = false;
        \Event::listen(UserTrainingCompleted::class, function ($event) use (&$listenerCalled){
            $listenerCalled = true;
        });

        $userTraining->update(['completed_at' => Carbon::parse('2019-11-01')]);
        $this->assertTrue($listenerCalled);
    }
    
    
}
