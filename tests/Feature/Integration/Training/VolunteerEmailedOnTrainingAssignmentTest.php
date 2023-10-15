<?php

namespace Tests\Feature\Integration\Training;

use App\Notifications\TrainingAssignedNotification;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
class VolunteerEmailedOnTrainingAssignmentTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function notification_sent_to_volunteer_when_training_assigned(): void
    {
        Notification::fake();

        $volunteer = factory(User::class)
            ->states('comprehensive', 'volunteer')
            ->create([]);

        $volunteer->userAptitudes()
            ->create([
                'aptitude_id' => 1,
            ]);

        Notification::assertSentTo(
            $volunteer,
            TrainingAssignedNotification::class,
            function ($notification, $channels) {
                return true;
            }
        );
    }
}
