<?php

namespace App\Listeners;

use App\Notification;
use App\Events\CommentCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateCommentNotification
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
     * @param  CommentCreated  $event
     * @return void
     */
    public function handle(CommentCreated $event)
    {
        $this->notification->compose(
            'COMMENT',
            $event->image->user,
            $event->image,
            $event->comment
        );
    }
}
