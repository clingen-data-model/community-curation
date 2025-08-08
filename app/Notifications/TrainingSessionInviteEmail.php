<?php

namespace App\Notifications;

use App\TrainingSession;
use Carbon\CarbonTimeZone;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

/**
 * @group training-sessions
 */
class TrainingSessionInviteEmail extends Notification
{
    use Queueable;

    protected $trainingSession;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TrainingSession $trainingSession)
    {
        $this->trainingSession = $trainingSession;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $tz = new CarbonTimeZone($notifiable->timezone);

        return (new MailMessage())
            ->subject($this->trainingSession->title . ' - ' . 
                $this->trainingSession->starts_at->addSeconds($tz->getOffset($this->trainingSession->starts_at))->format('F j, Y \a\t g:i a') . ' ' . strtoupper($tz->getAbbr()))
            ->attach($this->trainingSession->getIcsFilePath(), [
                'as' => Str::snake($this->trainingSession->title).'.ics',
                'mime' => 'text/calendar',
            ])
            ->view('email.training_session_invite', [
                'trainingSession' => $this->trainingSession,
                'volunteer' => $notifiable,
                'timezone' => $tz,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
        ];
    }
}
