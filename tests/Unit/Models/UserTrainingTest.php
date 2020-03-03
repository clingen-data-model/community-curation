<?php

namespace Tests\Unit\Models;

use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use App\Training;
use App\CurationActivity;
use App\Events\TrainingCompleted;
use App\Jobs\AssignVolunteerToAssignable;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group training
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
class TrainingTest extends TestCase
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

    /**
     * @test
     */
    public function disptatches_TrainingCompletedEvent_when_completed_at_changed_from_null()
    {
        $vol = factory(User::class)->states(['volunteer', 'baseline'])->create();
        $training = $vol->trainings()
                            ->create([
                                'aptitude_id' => 2,
                            ]);

        $listenerCalled = false;
        \Event::listen(TrainingCompleted::class, function ($event) use (&$listenerCalled) {
            $listenerCalled = true;
        });

        $training->update(['completed_at' => Carbon::parse('2019-11-01')]);
        $this->assertTrue($listenerCalled);
    }
}
