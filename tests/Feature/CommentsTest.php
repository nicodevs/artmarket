<?php

namespace Tests\Feature;

use App\Comment;
use App\Events\CommentCreated;
use App\Image;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_comment_an_image()
    {
        Event::fake();

        $user = factory(User::class)->create();
        $image = factory(Image::class, 'without_relationships')->make();
        $image = $user->images()->save($image);
        $comment = factory(Comment::class)->make();

        $this->actingAsUser(factory(User::class)->create())
            ->json('POST', 'api/images/' . $image->id . '/comments', $comment->toArray())
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'text' => $comment->text
                ],
                'success' => true
            ]);

        Event::assertDispatched(CommentCreated::class, function ($event) use ($comment) {
            return $event->comment->text === $comment->text;
        });
    }

    public function test_a_comment_has_required_fields()
    {
        $user = factory(User::class)->create();
        $image = factory(Image::class, 'without_relationships')->make();
        $image = $user->images()->save($image);

        $this->actingAsUser(factory(User::class)->create())
            ->json('POST', 'api/images/' . $image->id . '/comments', [])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'invalid_fields'
            ]);
    }

    public function test_admins_can_list_comments()
    {
        $comments = factory(Comment::class, 10)->make();
        foreach ($comments as $comment) {
            $comment->image_id = 2;
            $comment->user_id = 1;
            $comment->save();
        }

        $this->actingAsAdmin()
            ->json('GET', 'api/comments/')
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'text',
                        'user_id',
                        'image_id',
                        'created_at'
                    ]
                ]
            ]);
    }

    public function test_anyone_can_read_the_comments_of_an_image()
    {
        $author = factory(User::class)->create();
        $image = factory(Image::class, 'without_relationships')->create(['user_id' => $author->id]);

        $this->actingAsUser(factory(User::class)->create())
            ->json('POST', 'api/images/' . $image->id . '/comments', ['text' => 'Foo'])
            ->assertStatus(201);

        $this->actingAsUser(factory(User::class)->create())
            ->json('POST', 'api/images/' . $image->id . '/comments', ['text' => 'Bar'])
            ->assertStatus(201);

        $this->json('GET', 'api/images/' . $image->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
            ->assertJsonStructure([
                'data' => [
                    'comments' => [
                        '*' => [
                            'id',
                            'text',
                            'user_id',
                            'image_id',
                            'created_at',
                            'user' => [
                                'id',
                                'username',
                                'first_name',
                                'last_name',
                                'avatar'
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function test_an_admin_can_edit_a_comment()
    {
        $comment = factory(Comment::class)->make();
        $comment->image_id = 2;
        $comment->user_id = 1;
        $comment->save();

        $this->actingAsAdmin()
            ->json('PUT', 'api/comments/' . $comment->id, [
                'text' => 'Updated'
            ])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'text' => 'Updated'
                ]
            ]);
    }

    public function test_an_admin_can_delete_a_comment()
    {
        $comment = factory(Comment::class)->make();
        $comment->image_id = 2;
        $comment->user_id = 1;
        $comment->save();

        $this->actingAsAdmin()
            ->json('DELETE', 'api/comments/' . $comment->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_a_guest_can_not_create_comments()
    {
        $image = factory(Image::class, 'without_relationships')->create();

        $this->json('POST', 'api/images/' . $image->id . '/comments', [
                'text' => 'Foo'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function a_guest_can_not_edit_comments()
    {
        $comment = factory(Comment::class)->make();
        $comment->image_id = 2;
        $comment->user_id = 1;
        $comment->save();

        $this->json('PUT', 'api/comments/' . $comment->id, [
                'text' => 'Foo'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function a_guest_can_not_delete_comments()
    {
        $comment = factory(Comment::class)->make();
        $comment->image_id = 2;
        $comment->user_id = 1;
        $comment->save();

        $this->json('DELETE', 'api/comments/' . $comment->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }
}
