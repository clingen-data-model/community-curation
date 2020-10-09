<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttestationCreatedNotification extends Notification
{
    use Queueable;

    protected $attestation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($attestation)
    {
        $this->attestation = $attestation;
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
                    ->subject('You have a new attestation')
                    ->view(
                        'email.new_attestation',
                        [
                            'attestation' => $this->attestation,
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
