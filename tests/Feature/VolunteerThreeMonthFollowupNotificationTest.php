<?php

namespace Tests\Feature;

use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use App\CurationActivity;
use Illuminate\Support\Facades\Mail;
use App\Jobs\AssignVolunteerToAssignable;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ThreeMonthVolunteerFollowup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group comprehensive
 * @group 90-day-followup
 */
class VolunteerThreeMonthFollowupNotificationTest extends TestCase
{
    use DatabaseTransactions;

    public function setup():void
    {
        parent::setup();
    }

    /**
     * @test
     */
    public function comprehensive_volunteers_emailed_90_days_after_ep_assignment()
    {
        Notification::fake();
        $volunteer = factory(User::class)->states('volunteer', 'comprehensive')->create([]);
        $curationActivity = CurationActivity::all()->first();
        AssignVolunteerToAssignable::dispatch($volunteer, $curationActivity->expertPanels->first());
        
        Carbon::setTestNow(Carbon::now()->addDays(90));

        $this->artisan('volunteers:notify-3m-followup');
        
        Notification::assertSentTo([$volunteer], ThreeMonthVolunteerFollowup::class);
    }

    /**
     * @test
     */
    public function notification_renders_view()
    {
        $volunteer = factory(User::class)->states('volunteer', 'comprehensive')->create([]);
        $mailable = (new ThreeMonthVolunteerFollowup)->toMail($volunteer);
        $this->assertEquals('email.volunteers.three-month-followup', $mailable->view);
    }
}
