<?php

namespace App\Events;

use App\Image;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ImageSearched
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $keyword;
    public $query;
    public $user;

    /**
     * Create a new event instance.
     *
     * @param string $keyword
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $user
     * @return void
     */
    public function __construct($keyword, $query, $user)
    {
        $this->keyword = $keyword;
        $this->query = $query;
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
