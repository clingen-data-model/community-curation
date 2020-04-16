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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Notifications\VolunteerFollowup\FollowupReminder1;
use App\Notifications\VolunteerFollowup\FollowupReminder2;
use App\Notifications\VolunteerFollowup\InitialFollowupNotification;

/**
 * @group comprehensive
 * @group followup-notifications
 * @group 90-day-followup
 * @group 6-month-followup
 */
class VolunteerFollowupNotificationsTest extends TestCase
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

        $this->survey = class_survey()::findBySlug('volunteer-three-month1');
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

        $this->artisan('volunteers:notify-followup');
        
        Notification::assertSentTo([$this->volunteer, $this->otherVol], InitialFollowupNotification::class);
    }

    /**
     * @test
     */
    public function initial_notification_renders_correct_view()
    {
        $mailable = (new InitialFollowupNotification)->toMail($this->volunteer);
        $this->assertEquals('email.volunteers.followups.initial_notification', $mailable->view);
    }

    /**
     * @test
     */
    public function sends_remind_1_97_days_after_ep_assignment_if_not_yet_responded()
    {
        Notification::fake();
        Carbon::setTestNow(Carbon::now()->addDays(97));

        $this->artisan('volunteers:notify-followup');
        
        Notification::assertSentTo([$this->volunteer], FollowupReminder1::class);

        Notification::assertNotSentTo([$this->otherVol], FollowupReminder1::class);
    }

    /**
     * @test
     */
    public function remind_1_renders_correct_view()
    {
        $mailable = (new FollowupReminder1)->toMail($this->volunteer);
        $this->assertEquals('email.volunteers.followups.reminder_1', $mailable->view);
    }

    /**
     * @test
     */
    public function sends_remind_2_111_days_after_ep_assignment_if_not_yet_responded()
    {
        Notification::fake();
        Carbon::setTestNow(Carbon::now()->addDays(111));

        $this->artisan('volunteers:notify-followup');
        
        Notification::assertSentTo([$this->volunteer], FollowupReminder2::class);

        Notification::assertNotSentTo([$this->otherVol], FollowupReminder2::class);
    }

    /**
     * @test
     */
    public function remind_2_renders_correct_view()
    {
        $mailable = (new FollowupReminder2)->toMail($this->volunteer);
        $this->assertEquals('email.volunteers.followups.reminder_2', $mailable->view);
    }
}
