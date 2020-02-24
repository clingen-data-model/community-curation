<?php

namespace App\Events\Volunteers;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MarkedBaseline
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $volunteer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $volunteer)
    {
        //
        $this->volunteer = $volunteer;
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
