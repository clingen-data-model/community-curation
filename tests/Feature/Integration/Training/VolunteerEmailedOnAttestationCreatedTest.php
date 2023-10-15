<?php

namespace Tests\Feature\Integration\Training;

use App\Notifications\AttestationCreatedNotification;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
class VolunteerEmailedOnAttestationCreatedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function notification_sent_to_volunteer_when_training_assigned()
    {
        Notification::fake();

        $volunteer = factory(User::class)
            ->states('comprehensive', 'volunteer')
            ->create([]);

        $volunteer->attestations()
            ->create([
                'aptitude_id' => 1,
            ]);

        Notification::assertSentTo(
            $volunteer,
            AttestationCreatedNotification::class,
            function ($notification, $channels) {
                return true;
            }
        );
    }
}
