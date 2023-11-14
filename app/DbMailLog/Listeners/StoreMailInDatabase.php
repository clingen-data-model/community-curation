<?php

namespace App\DbMailLog\Listeners;

use App\DbMailLog\DbMailLogProvider;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
     * @param  MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        $email = DbMailLogProvider::getEmailInstance([
            'from' => $this->addressesToHash($event->message->getFrom()),
            'sender' => $this->addressesToHash([$event->message->getSender()]),
            'reply_to' => $this->addressesToHash($event->message->getReplyTo()),
            'to' => $this->addressesToHash($event->message->getTo()),
            'cc' => $this->addressesToHash($event->message->getCc()),
            'bcc' => $this->addressesToHash($event->message->getBcc()),
            'subject' => $event->message->getSubject(),
            'body' => $event->message->getTextBody(),
        ]);
        $email->save();
    }

    private function addressesToHash(Array|Address $addresses): array|null
    {
        $hash = [];
        $presentAddresses = array_filter($addresses);
        array_walk($presentAddresses, function ($address) use (&$hash) {
            $hash[$address->getAddress()] = $address->getName();
        });

        if (count($hash) == 0) {
            return null;
        }

        return $hash;
    }
}
