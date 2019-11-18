<?php

namespace App\Events;

use App\User;
use App\Aptitude;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AptitudeGranted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $aptitude;

    protected $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Aptitude $aptitude, User $user)
    {
        //
        $this->aptitude = $aptitude;
        $this->user = $user;
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
