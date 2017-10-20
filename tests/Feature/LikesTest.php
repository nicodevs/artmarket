<?php

namespace Tests\Feature;

use App\Events\ImageLiked;
use App\Image;
use App\Like;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LikesTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_like_an_image()
    {
        Event::fake();

        $author = factory(User::class)->create();
        $image = factory(Image::class, 'without_relationships')->make();
        $image = $author->images()->save($image);

        $user = factory(User::class)->create();
        $this->actingAsUser($user)
            ->json('POST', 'api/images/' . $image->id . '/likes')
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'image_id' => $image->id,
                    'user_id' => $user->id
                ]
            ]);

        Event::assertDispatched(ImageLiked::class, function ($event) use ($image) {
            return $event->image->id === $image->id;
        });

        $this->assertDatabaseHas('likes', [
            'image_id' => $image->id
        ]);
    }

    public function test_a_user_can_dislike_an_image()
    {
        $author = factory(User::class)->create();
        $image = factory(Image::class, 'without_relationships')->make();
        $image = $author->images()->save($image);

        $user = factory(User::class)->create();

        $this->actingAsUser($user)
            ->json('POST', 'api/images/' . $image->id . '/likes')
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'image_id' => $image->id,
                    'user_id' => $user->id
                ]
            ]);

        $this->actingAsUser($user)
            ->json('DELETE', 'api/images/' . $image->id . '/likes')
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseMissing('likes', [
            'image_id' => $image->id
        ]);
    }
}
