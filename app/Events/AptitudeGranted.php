<?php

namespace App\Events;

use App\Aptitude;
use App\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AptitudeGranted
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    protected $aptitude;

    protected $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Aptitude $aptitude, User $user)
    {
        $this->aptitude = $aptitude;
        $this->user = $user;
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
