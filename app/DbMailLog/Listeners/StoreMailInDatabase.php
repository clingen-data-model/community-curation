<?php

namespace App\DbMailLog\Listeners;

use App\DbMailLog\DbMailLogProvider;
use Illuminate\Mail\Events\SentMessage;

class StoreMailInDatabase
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(SentMessage $event)
    {
        $email = DbMailLogProvider::getEmailInstance([
            'from' => $event->message->getFrom(),
            'sender' => $event->message->getSender(),
            'reply_to' => $event->message->getReplyTo(),
            'to' => $event->message->getTo(),
            'cc' => $event->message->getCc(),
            'bcc' => $event->message->getBcc(),
            'subject' => $event->message->getSubject(),
            'body' => $event->message->getBody(),
        ]);
        $email->save();
    }
}
