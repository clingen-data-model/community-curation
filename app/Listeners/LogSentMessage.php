<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Events\MessageSent;

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
    public function handle(MessageSent $event)
    {
        $messageInfo = [
            'to' => $event->message->getTo(),
            'from' => $event->message->getFrom(),
            'subject' => $event->message->getSubject(),
            'body' => $event->message->getHtmlBody() ?? $event->message->getTextBody(),
        ];

        Log::channel('mail')->info('Email sent', $messageInfo);
    }
}
