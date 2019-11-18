<?php

namespace App\Events;

use App\Attestation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AttestationCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $attestation;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Attestation $attestation)
    {
        //
        $this->attestation = $attestation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
