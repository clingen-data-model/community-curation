<?php

namespace App\Console\Commands\Volunteers;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VolunteerFollowup\FollowupReminder1;
use App\Notifications\VolunteerFollowup\FollowupReminder2;
use App\Notifications\VolunteerFollowup\InitialFollowupNotification;

class SendFollowupNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'volunteers:notify-followup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications to comprehensive volunteers that were assigned to an expert panel 90 days ago.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->sendInitialNotification();
        $this->sendFollowup1();
        $this->sendFollowup2();
    }

    private function sendInitialNotification()
    {
        $recipientQuery = $this->buildRecipientQuery(Carbon::today()->subDays(90));

        $recipients = $recipientQuery->get();

        Notification::send($recipients, new InitialFollowupNotification());
    }

    private function sendFollowup1()
    {
        $recipientQuery = $this->buildRecipientQuery(Carbon::today()->subDays(97))
                        ->whereDoesntHave('Volunteer3MonthSurvey', function ($q) {
                            $q->whereNotNull('finalized_at');
                        });

        $recipients = $recipientQuery->get();

        Notification::send($recipients, new FollowupReminder1());
    }

    private function sendFollowup2()
    {
        $recipientQuery = $this->buildRecipientQuery(Carbon::today()->subDays(111))
                            ->whereDoesntHave('Volunteer3MonthSurvey', function ($q) {
                                $q->whereNotNull('finalized_at');
                            });

        $recipients = $recipientQuery->get();

        Notification::send($recipients, new FollowupReminder2());
    }

    private function buildRecipientQuery(Carbon $date)
    {
        return User::isVolunteer()
        ->whereHas('assignments', function ($q) use ($date) {
            $q->expertPanel()
                ->whereDate('created_at', $date);
        });
    }
}
