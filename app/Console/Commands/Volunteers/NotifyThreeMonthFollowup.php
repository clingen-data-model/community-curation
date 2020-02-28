<?php

namespace App\Console\Commands\Volunteers;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ThreeMonthVolunteerFollowup;
use App\Notifications\ThreeMonthVolunteerReminder1;
use App\Notifications\ThreeMonthVolunteerReminder2;

class NotifyThreeMonthFollowup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'volunteers:notify-3m-followup';

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

        Notification::send($recipients, new ThreeMonthVolunteerFollowup());
    }

    private function sendFollowup1()
    {
        $recipientQuery = $this->buildRecipientQuery(Carbon::today()->subDays(97))
                        ->whereDoesntHave('followup3mVolunteer', function ($q) {
                            $q->whereNotNull('finalized_at');
                        });

        $recipients = $recipientQuery->get();

        Notification::send($recipients, new ThreeMonthVolunteerReminder1());
    }

    private function sendFollowup2()
    {
        $recipientQuery = $this->buildRecipientQuery(Carbon::today()->subDays(111))
                            ->whereDoesntHave('followup3mVolunteer', function ($q) {
                                $q->whereNotNull('finalized_at');
                            });

        $recipients = $recipientQuery->get();

        Notification::send($recipients, new ThreeMonthVolunteerReminder2());
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
