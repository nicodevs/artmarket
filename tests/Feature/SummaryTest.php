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

    public function test_daily_summary()
    {
        $users = factory(User::class, 10)->create()->pluck('id')->all();
        $images = factory(Image::class, 'without_relationships', 10)->create()->pluck('id')->all();

        $notifications = Collection::times(10, function ($number) use ($users, $images) {
            return factory(Notification::class)->create([
                'image_id' => $images[array_rand($images)],
                'user_id' => $users[array_rand($users)]
            ]);
        });

        $this->json('GET', 'api/notifications/send/daily')
            ->assertStatus(200)
            ->assertJson([
                'success' => true
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
                        'notification_counters',
                        'email' => [
                            'subject',
                            'recipient',
                            'body'
                        ]
                    ]
                ]
            ]);
    }
}