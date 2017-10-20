<?php

namespace Tests\Feature;

use App\Notification;
use App\User;
use App\Image;
use App\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_comment_notification_can_be_composed()
    {
        $author = factory(User::class)->create();
        $image = factory(Image::class, 'without_relationships')->create(['user_id' => $author->id]);
        $commenter = factory(User::class)->create();
        $comment = factory(Comment::class)->make(['image_id' => $image->id, 'user_id' => $commenter->id]);

        $notification = new Notification;
        $notification->compose('COMMENT', $image->user, $image, $comment);

        $this->assertDatabaseHas('notifications', [
            'type' => 'COMMENT'
        ]);
    }

    public function test_a_like_notification_can_be_composed()
    {
        $author = factory(User::class)->create();
        $image = factory(Image::class, 'without_relationships')->create(['user_id' => $author->id]);
        $liker = factory(User::class)->create();
        $like = $liker->likesImage($image);

        $notification = new Notification;
        $notification->compose('LIKE', $image->user, $image, $like);

        $this->assertDatabaseHas('notifications', [
            'type' => 'LIKE'
        ]);
    }
}
