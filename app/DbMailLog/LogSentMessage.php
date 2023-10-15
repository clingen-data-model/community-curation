<?php

namespace App\Listeners;

use Illuminate\Mail\Events\SentMessage;

class LogSentMessage
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(SentMessage $event): void
    {
        $messageInfo = [
            'to' => $event->message->getTo(),
            'from' => $event->message->getFrom(),
            'subject' => $event->message->getSubject(),
            'body' => $event->message->getBody(),
        ];

        \Log::channel('mail')->info('Email sent', $messageInfo);
    }
}
