<?php

namespace App\Notifications;

use App\UserAptitude;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrainingAssignedNotification extends Notification
{
    use Queueable;

    protected $training;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(UserAptitude $training)
    {
        $this->training = $training;
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
                    ->subject('Training Assignment Notification.')
                    ->view(
                        'email.training_assigned',
                        [
                            'training' => $this->training,
                            'recipient' => $notifiable,
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
