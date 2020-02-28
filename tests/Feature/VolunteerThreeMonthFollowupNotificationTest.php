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
use App\Notifications\ThreeMonthVolunteerReminder1;
use App\Notifications\ThreeMonthVolunteerReminder2;
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
        $this->volunteer = factory(User::class)->states('volunteer', 'comprehensive')->create([]);
        $this->otherVol = factory(User::class)->states('volunteer', 'comprehensive')->create([]);
        
        $curationActivity = CurationActivity::all()->first();
        AssignVolunteerToAssignable::dispatch($this->volunteer, $curationActivity->expertPanels->first());
        AssignVolunteerToAssignable::dispatch($this->otherVol, $curationActivity->expertPanels->first());

        $this->survey = class_survey()::findBySlug('threemonthvolunteerfollowup1');
        $this->rsp = $this->survey->getNewResponse($this->otherVol);
        $this->rsp->finalize();
    }

    /**
     * @test
     */
    public function comprehensive_volunteers_emailed_90_days_after_ep_assignment()
    {
        Notification::fake();
        Carbon::setTestNow(Carbon::now()->addDays(90));

        $this->artisan('volunteers:notify-3m-followup');
        
        Notification::assertSentTo([$this->volunteer, $this->otherVol], ThreeMonthVolunteerFollowup::class);
    }

    /**
     * @test
     */
    public function initial_notification_renders_correct_view()
    {
        $mailable = (new ThreeMonthVolunteerFollowup)->toMail($this->volunteer);
        $this->assertEquals('email.volunteers.three_month_followup.initial_notification', $mailable->view);
    }

    /**
     * @test
     */
    public function sends_remind_1_97_days_after_ep_assignment_if_not_yet_responded()
    {
        Notification::fake();
        Carbon::setTestNow(Carbon::now()->addDays(97));

        $this->artisan('volunteers:notify-3m-followup');
        
        Notification::assertSentTo([$this->volunteer], ThreeMonthVolunteerReminder1::class);

        Notification::assertNotSentTo([$this->otherVol], ThreeMonthVolunteerReminder1::class);
    }

    /**
     * @test
     */
    public function remind_1_renders_correct_view()
    {
        $mailable = (new ThreeMonthVolunteerReminder1)->toMail($this->volunteer);
        $this->assertEquals('email.volunteers.three_month_followup.reminder_1', $mailable->view);
    }

    /**
     * @test
     */
    public function sends_remind_2_111_days_after_ep_assignment_if_not_yet_responded()
    {
        Notification::fake();
        Carbon::setTestNow(Carbon::now()->addDays(111));

        $this->artisan('volunteers:notify-3m-followup');
        
        Notification::assertSentTo([$this->volunteer], ThreeMonthVolunteerReminder2::class);

        Notification::assertNotSentTo([$this->otherVol], ThreeMonthVolunteerReminder2::class);
    }

    /**
     * @test
     */
    public function remind_2_renders_correct_view()
    {
        $mailable = (new ThreeMonthVolunteerReminder2)->toMail($this->volunteer);
        $this->assertEquals('email.volunteers.three_month_followup.reminder_2', $mailable->view);
    }
}
