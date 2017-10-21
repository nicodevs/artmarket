<?php

namespace App\Listeners;

use App\Events\ImageApproved;
use App\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateApprovalNotification
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
     * @param  ImageApproved  $event
     * @return void
     */
    public function handle(ImageApproved $event)
    {
        $this->notification->compose(
            'APPROVAL',
            $event->image->user,
            $event->image
        );
    }
}
