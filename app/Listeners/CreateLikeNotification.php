<?php

namespace App\Listeners;

use App\Events\ImageLiked;
use App\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateLikeNotification
{
    public $notification;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Handle the event.
     *
     * @param  ImageLiked  $event
     * @return void
     */
    public function handle(ImageLiked $event)
    {
        $this->notification->compose(
            'LIKE',
            $event->image->user,
            $event->image,
            $event->like
        );
    }
}
