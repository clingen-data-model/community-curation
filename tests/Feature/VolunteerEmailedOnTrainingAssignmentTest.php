<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\CurationActivity;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\TrainingAssignedNotification;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VolunteerEmailedOnTrainingAssignmentTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function notification_sent_to_volunteer_when_training_assigned()
    {
        \Notification::fake();

        $volunteer = factory(User::class)
                        ->states('comprehensive', 'volunteer')
                        ->create([]);

        $volunteer->trainings()
            ->create([
                'aptitude_id' => 1,
            ]);

        \Notification::assertSentTo(
            $volunteer,
            TrainingAssignedNotification::class,
            function ($notification, $channels) {
                return true;
            }
        );
    }
    
}
