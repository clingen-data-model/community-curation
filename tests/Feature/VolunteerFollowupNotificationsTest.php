<?php

namespace Tests\Feature;

use App\CurationActivity;
use App\Jobs\AssignVolunteerToAssignable;
use App\Notifications\VolunteerFollowup\FollowupReminder1;
use App\Notifications\VolunteerFollowup\FollowupReminder2;
use App\Notifications\VolunteerFollowup\InitialFollowupNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * @group comprehensive
 * @group followup-notifications
 * @group 90-day-followup
 * @group 6-month-followup
 */
class VolunteerFollowupNotificationsTest extends TestCase
{
    use DatabaseTransactions;

    public function setup(): void
    {
        parent::setup();
        Carbon::setTestNow(Carbon::today());
        //Create 90 day volunteer
        $this->volunteer90 = factory(User::class)->states('active-volunteer', 'comprehensive')->create([]);
        $this->otherVol90 = factory(User::class)->states('active-volunteer', 'comprehensive')->create([]);

        $curationActivity = CurationActivity::all()->first();
        AssignVolunteerToAssignable::dispatch($this->volunteer90, $curationActivity->curationGroups->first());
        AssignVolunteerToAssignable::dispatch($this->otherVol90, $curationActivity->curationGroups->first());

        $this->survey = class_survey()::findBySlug('volunteer-three-month1');
        $this->rsp = $this->survey->getNewResponse($this->otherVol90);
        $this->rsp->finalize();

        // create 6 month volunteer
        $this->volunteer6m = factory(User::class)->states('active-volunteer', 'comprehensive')->create([]);
        $this->otherVol6m = factory(User::class)->states('active-volunteer', 'comprehensive')->create([]);

        $curationActivity = CurationActivity::all()->first();
        AssignVolunteerToAssignable::dispatch($this->volunteer6m, $curationActivity->curationGroups->first());
        AssignVolunteerToAssignable::dispatch($this->otherVol6m, $curationActivity->curationGroups->first());

        $this->survey = class_survey()::findBySlug('volunteer-six-month1');
        $this->rsp = $this->survey->getNewResponse($this->otherVol6m);
        $this->rsp->finalize();
    }

    /**
     * @test
     */
    public function comprehensive_volunteers_emailed_90_days_after_ep_assignment(): void
    {
        Notification::fake();

        Carbon::setTestNow(Carbon::now()->addDays(90));

        $this->artisan('volunteers:notify-followup');

        Notification::assertSentTo([$this->volunteer90, $this->otherVol90], InitialFollowupNotification::class);
    }

    /**
     * @test
     */
    public function comprehensive_volunteers_emailed_182_days_after_ep_assignment(): void
    {
        Notification::fake();
        Carbon::setTestNow(Carbon::now()->addDays(182));

        $this->artisan('volunteers:notify-followup');

        Notification::assertSentTo([$this->volunteer6m, $this->otherVol6m], InitialFollowupNotification::class);
    }

    /**
     * @test
     */
    public function initial_notification_renders_correct_view_with_correct_url(): void
    {
        $mailable90 = (new InitialFollowupNotification(url('/volunteer-three-month')))->toMail($this->volunteer90);
        $this->assertEquals('email.volunteers.followups.initial_notification', $mailable90->view);
        $this->assertStringContainsStringIgnoringCase(url('/volunteer-three-month'), $mailable90->render());

        $mailable6m = (new InitialFollowupNotification(url('/volunteer-six-month')))->toMail($this->volunteer6m);
        $this->assertEquals('email.volunteers.followups.initial_notification', $mailable6m->view);
        $this->assertStringContainsStringIgnoringCase(url('/volunteer-six-month'), $mailable6m->render());
    }

    /**
     * @test
     */
    public function sends_remind_1_97_days_after_ep_assignment_if_not_yet_responded(): void
    {
        Notification::fake();
        Carbon::setTestNow(Carbon::now()->addDays(97));

        $this->artisan('volunteers:notify-followup');

        Notification::assertSentTo([$this->volunteer90], FollowupReminder1::class);

        Notification::assertNotSentTo([$this->otherVol90], FollowupReminder1::class);
    }

    /**
     * @test
     */
    public function sends_remind_1_189_days_after_ep_assignment_if_not_yet_responded(): void
    {
        Notification::fake();
        Carbon::setTestNow(Carbon::now()->addDays(189));

        $this->artisan('volunteers:notify-followup');

        Notification::assertSentTo([$this->volunteer6m], FollowupReminder1::class);

        Notification::assertNotSentTo([$this->otherVol6m], FollowupReminder1::class);
    }

    /**
     * @test
     */
    public function remind_1_renders_correct_view_with_correct_url(): void
    {
        $mailable90 = (new FollowupReminder1(url('/volunteer-three-month')))->toMail($this->volunteer90);
        $this->assertEquals('email.volunteers.followups.reminder_1', $mailable90->view);
        $this->assertStringContainsStringIgnoringCase(url('/volunteer-three-month'), $mailable90->render());

        $mailable6m = (new FollowupReminder1(url('/volunteer-six-month')))->toMail($this->volunteer6m);
        $this->assertEquals('email.volunteers.followups.reminder_1', $mailable6m->view);
        $this->assertStringContainsStringIgnoringCase(url('/volunteer-six-month'), $mailable6m->render());
    }

    /**
     * @test
     */
    public function sends_remind_2_111_days_after_ep_assignment_if_not_yet_responded(): void
    {
        Notification::fake();
        Carbon::setTestNow(Carbon::now()->addDays(111));

        $this->artisan('volunteers:notify-followup');

        Notification::assertSentTo([$this->volunteer90], FollowupReminder2::class);

        Notification::assertNotSentTo([$this->otherVol90], FollowupReminder2::class);
    }

    /**
     * @test
     */
    public function sends_remind_2_203_days_after_ep_assignment_if_not_yet_responded(): void
    {
        Notification::fake();
        Carbon::setTestNow(Carbon::now()->addDays(203));

        $this->artisan('volunteers:notify-followup');

        Notification::assertSentTo([$this->volunteer6m], FollowupReminder2::class);

        Notification::assertNotSentTo([$this->otherVol6m], FollowupReminder2::class);
    }

    /**
     * @test
     */
    public function remind_2_renders_correct_view_with_correct_url(): void
    {
        $mailable90 = (new FollowupReminder2(url('/volunteer-three-month')))->toMail($this->volunteer90);
        $this->assertEquals('email.volunteers.followups.reminder_2', $mailable90->view);
        $this->assertStringContainsStringIgnoringCase(url('/volunteer-three-month'), $mailable90->render());

        $mailable6m = (new FollowupReminder2(url('/volunteer-six-month')))->toMail($this->volunteer6m);
        $this->assertEquals('email.volunteers.followups.reminder_2', $mailable6m->view);
        $this->assertStringContainsStringIgnoringCase(url('/volunteer-six-month'), $mailable6m->render());
    }

    /**
     * @test
     */
    public function run_actual_comand_to_see_output(): void
    {
        Carbon::setTestNow(Carbon::now()->addDays(203));
        $this->artisan('volunteers:notify-followup');
        $this->assertTrue(true);
    }
}
