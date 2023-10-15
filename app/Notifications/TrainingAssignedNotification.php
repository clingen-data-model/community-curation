<?php

namespace App\Notifications;

use App\UserAptitude;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrainingAssignedNotification extends Notification
{
    use Queueable;

    protected $userAptitude;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(UserAptitude $userAptitude)
    {
        $this->userAptitude = $userAptitude;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Training Assignment Notification.')
            ->view(
                'email.training_assigned',
                [
                    'userAptitude' => $this->userAptitude,
                    'recipient' => $notifiable,
                ]
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toArray($notifiable): array
    {
        return [
        ];
    }
}
