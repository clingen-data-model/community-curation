<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\AttestationCreatedNotification;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
