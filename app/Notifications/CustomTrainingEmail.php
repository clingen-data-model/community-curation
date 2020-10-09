<?php

namespace App\Notifications;

use App\TrainingSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomTrainingEmail extends Notification
{
    use Queueable;

    public $fromEmail;

    public $subject;

    public $body;

    public $trainingSession;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TrainingSession $trainingSession, $body, $fromEmail = null, $subject = null)
    {
        $this->trainingSession = $trainingSession;
        $this->fromEmail = $fromEmail ?? config('mail.from.address');
        $this->subject = $subject ?? 'A note about your ClinGen volunteer training on '.$trainingSession->starts_at->format('D, M j, Y');
        $this->body = $body;
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
            ->subject($this->subject)
            ->from($this->fromEmail)
            ->view(
                'email.training_message_custom',
                [
                    'body' => $this->body,
                    'trainingSession' => $this->trainingSession,
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
