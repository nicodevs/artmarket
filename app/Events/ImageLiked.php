<?php

namespace App\Events;

use App\Like;
use App\Image;
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
        $this->like = $like;
        $this->image = $image;
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
