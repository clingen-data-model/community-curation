<?php

namespace Tests\Unit\Models;

use App\CurationActivity;
use App\Events\TrainingCompleted;
use App\Jobs\AssignVolunteerToAssignable;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * @group training
 * @group userAptitudes
 *
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
class UserAptitudeTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function userAptitude_can_get_its_related_assignment()
    {
        $vol = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
        AssignVolunteerToAssignable::dispatch($vol, CurationActivity::find(1));

        $assignment = $vol->fresh()->assignments->first();
        $userAptitude = $vol->userAptitudes->first();

        $this->assertEquals($assignment->id, $userAptitude->assignment->id);
    }

    /**
     * @test
     */
    public function disptatches_TrainingCompletedEvent_when_trained_at_changed_from_null()
    {
        $vol = factory(User::class)->states(['volunteer', 'baseline'])->create();
        $userApt = $vol->userAptitudes()
            ->create([
                'aptitude_id' => 2,
            ]);

        $listenerCalled = false;
        Event::listen(TrainingCompleted::class, function ($event) use (&$listenerCalled) {
            $listenerCalled = true;
        });

        $userApt->update(['trained_at' => Carbon::parse('2019-11-01')]);
        $this->assertTrue($listenerCalled);
    }
}
