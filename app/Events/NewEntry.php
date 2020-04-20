<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewEntry implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $battle_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($battle_id)
    {
        $this->battle_id = $battle_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('entries');
    }
}
