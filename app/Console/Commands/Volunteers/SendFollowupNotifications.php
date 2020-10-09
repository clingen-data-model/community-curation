<?php

namespace App\Console\Commands\Volunteers;

use App\Notifications\VolunteerFollowup\FollowupReminder1;
use App\Notifications\VolunteerFollowup\FollowupReminder2;
use App\Notifications\VolunteerFollowup\InitialFollowupNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

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
    protected $description = 'Send notifications to comprehensive volunteers that were assigned to an curation group 90 days ago.';

    /**
     * URL for three month survey.
     *
     * @var string
     */
    private $threeMonthUrl;

    /**
     * URL for three month survey.
     *
     * @var string
     */
    private $sixMonthUrl;

    /**
     * Survey to notify about and day to send initial notification.
     *
     * @var array
     */
    private $followups;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->followups = [
            [
                'days' => 90,
                'survey' => 'Volunteer3MonthSurvey',
                'url' => url('/volunteer-three-month'),
            ],
            [
                'days' => 182,
                'survey' => 'Volunteer6MonthSurvey',
                'url' => url('/volunteer-six-month'),
            ],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->followups as $value) {
            extract($value);
            $this->sendInitialNotification($days, $url);
            $this->sendFollowup1(($days + 7), $survey, $url);
            $this->sendFollowup2(($days + 21), $survey, $url);
        }
    }

    private function sendInitialNotification($days, $url)
    {
        $recipientQuery = $this->buildRecipientQuery(Carbon::today()->subDays($days));
        $recipients = $recipientQuery->get();
        Notification::send($recipients, new InitialFollowupNotification($url));
    }

    private function sendFollowup1($days, $survey, $url)
    {
        $recipientQuery = $this->buildRecipientQuery(Carbon::today()->subDays($days))
                        ->whereDoesntHave($survey, function ($q) {
                            $q->whereNotNull('finalized_at');
                        });

        $recipients = $recipientQuery->get();

        Notification::send($recipients, new FollowupReminder1($url));
    }

    private function sendFollowup2($days, $survey, $url)
    {
        $recipientQuery = $this->buildRecipientQuery(Carbon::today()->subDays($days))
                            ->whereDoesntHave($survey, function ($q) {
                                $q->whereNotNull('finalized_at');
                            });

        $recipients = $recipientQuery->get();

        Notification::send($recipients, new FollowupReminder2($url));
    }

    private function buildRecipientQuery(Carbon $date)
    {
        return User::isVolunteer()
        ->whereHas('assignments', function ($q) use ($date) {
            //we only want  who's first curation_group was assigned on the date
            $q->curationGroup()
                ->select('user_id')
                ->selectRaw('DATE(MIN(created_at)) as min_date')
                ->groupBy('user_id')
                ->having('min_date', $date);
        });
    }
}
