<?php

namespace App\Events;

use App\Image;
use App\Like;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ImageLiked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $image;
    public $like;

    /**
     * Create a new event instance.
     *
     * @param App\Like $like
     * @return void
     */
    public function __construct(Image $image, Like $like)
    {
        $this->image = $image;
        $this->like = $like;
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
