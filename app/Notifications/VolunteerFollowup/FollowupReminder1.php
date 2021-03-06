<?php

namespace App\Notifications\VolunteerFollowup;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowupReminder1 extends Notification
{
    use Queueable;

    protected $surveyUrl;

    /**
     * Create a new notification instance.
     *
     * @param string $surveyUrl URL for volunteer to complete survey
     *
     * @return void
     */
    public function __construct($surveyUrl)
    {
        $this->surveyUrl = $surveyUrl;
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
        return (new MailMessage())
                    ->view(
                        'email.volunteers.followups.reminder_1',
                        [
                            'volunteer' => $notifiable,
                            'url' => $this->surveyUrl,
                        ]
                    );
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
