<?php

namespace Tests\Feature;

use App\Events\UserRegistered;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register()
    {
        Event::fake();

        $user = factory(User::class)->make();

        $response = $this->json('POST', 'api/auth/signup', [
            'name' => 'Test User',
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'first_name' => 'Test',
                    'last_name' => 'User',
                    'email' => $user->email
                ],
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => ['token']
            ]);

        Event::assertDispatched(UserRegistered::class, function ($event) use ($user) {
            return $event->user->email === $user->email;
        });
    }
}
