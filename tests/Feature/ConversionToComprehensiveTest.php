<?php

namespace Tests\Feature;

use App\Notifications\ConversionToComprehensive;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ConversionToComprehensiveTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function does_not_send_notification_if_not_baseline()
    {
        Notification::fake();
        $volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create([]);
        Notification::assertNotSentTo(
            $volunteer,
            ConversionToComprehensive::class
        );
    }

    /**
     * @test
     */
    public function Volunteer_notified_of_conversion_via_email()
    {
        $volunteer = factory(User::class)->states(['volunteer', 'baseline'])->create([]);
        Notification::fake();
        $volunteer->update(['volunteer_type_id' => config('volunteers.types.comprehensive')]);

        Notification::assertSentTo(
            $volunteer,
            ConversionToComprehensive::class,
            function ($notification, $channels) use ($volunteer) {
                $rendered = $notification->toMail($volunteer)->render();

                return in_array('mail', $channels)
                    && $notification->volunteer->email == $volunteer->email
                    && $rendered == view('email.conversion_to_comprehensive', ['volunteer' => $volunteer])
                ;
            }
        );
    }
}
