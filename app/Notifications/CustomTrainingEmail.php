<?php

namespace App\Notifications;

use App\TrainingSession;
use Carbon\CarbonTimeZone;
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

    protected $attachments;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TrainingSession $trainingSession, $body, $fromEmail = null, $subject = null, $attachments = [])
    {
        $this->trainingSession = $trainingSession;
        $this->fromEmail = $fromEmail ?? config('mail.from.address');
        $this->subject = $subject;
        $this->body = $body;
        $this->attachments = $attachments;
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
        $timezone = new CarbonTimeZone($notifiable->timezone);
        $subject = $this->subject;
        if (! $subject) {
            $subject = 'A note about your ClinGen volunteer training on '.$this->trainingSession->starts_at
                ->addSeconds($timezone->getOffset($this->trainingSession->starts_at))
                ->format('l, F j, Y \a\t g:i a');
        }

        $mail = (new MailMessage())
            ->subject($subject)
            ->from($this->fromEmail)
            ->view(
                'email.training_message_custom',
                [
                    'body' => $this->body,
                    'trainingSession' => $this->trainingSession,
                    'timezone' => $timezone,
                ]
            );

        if (count($this->attachments) > 0) {
            foreach ($this->attachments as $attachment) {
                $mail->attach($attachment->getPath(), [
                    'as' => $attachment->getOriginalName(),
                ]);
            }
        }

        return $mail;
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
