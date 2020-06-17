<?php

namespace App\Notifications;

use App\TrainingSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

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
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->view('email.training_session_invite', ['trainingSession' => $this->trainingSession, 'volunteer' => $notifiable]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
