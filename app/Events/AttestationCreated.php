<?php

namespace App\Events;

use App\Attestation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttestationCreated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $attestation;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Attestation $attestation)
    {
        $this->attestation = $attestation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): array
    {
        return new PrivateChannel('channel-name');
    }
}
