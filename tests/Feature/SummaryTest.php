<?php

namespace Tests\Feature;

use App\Image;
use App\Notification;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class SummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_approvals_summary()
    {
        $user = factory(User::class)->create(['email' => 'tester@example.com']);
        $image = factory(Image::class, 'without_relationships', 10)->create(['user_id' => $user->id]);

        Collection::times(10, function ($number) use ($user) {
            return factory(Notification::class)->create([
                'image_id' => $number,
                'user_id' => $user->id,
                'type' => 'APPROVAL'
            ]);
        });

        $response = $this->json('GET', 'api/summary?type=approvals')
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    [
                        'notification_counters' => [
                            'APPROVAL' => 10
                        ]
                    ]
                ]
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'user' => [
                            'id',
                            'email'
                        ],
                        'notifications' => [
                            '*' => [
                                'id',
                                'user_id',
                                'image_id',
                                'type',
                                'description'
                            ]
                        ],
                        'notification_counters'
                    ]
                ]
            ]);

        $this->assertDatabaseHas('emails', [
            'recipient' => 'tester@example.com'
        ]);
    }

    public function test_interactions_summary()
    {
        $user = factory(User::class)->create(['email' => 'tester@example.com']);
        $image = factory(Image::class, 'without_relationships', 10)->create(['user_id' => $user->id]);

        Collection::times(5, function ($number) use ($user) {
            return factory(Notification::class)->create([
                'image_id' => $number,
                'user_id' => $user->id,
                'type' => 'LIKE',
                'description' => 'Foo'
            ]);
        });

        Collection::times(5, function ($number) use ($user) {
            return factory(Notification::class)->create([
                'image_id' => $number,
                'user_id' => $user->id,
                'type' => 'COMMENT',
                'description' => 'Foo'
            ]);
        });

        $response = $this->json('GET', 'api/summary?type=interactions')
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    [
                        'notification_counters' => [
                            'COMMENT' => 5,
                            'LIKE' => 5
                        ]
                    ]
                ]
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'user' => [
                            'id',
                            'email'
                        ],
                        'notifications' => [
                            '*' => [
                                'id',
                                'user_id',
                                'image_id',
                                'type',
                                'description'
                            ]
                        ],
                        'notification_counters'
                    ]
                ]
            ]);

        $this->assertDatabaseHas('emails', [
            'recipient' => 'tester@example.com'
        ]);
    }

    public function test_an_invalid_summary_type_returns_error()
    {
        $response = $this->json('GET', 'api/summary?type=foo')
            ->assertStatus(422);
    }
}