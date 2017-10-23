<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class PasswordRecoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_request_a_password_reset()
    {
        $user = factory(User::class)->create();

        $this->json('POST', 'api/password/recovery', [
                'email' => $user->email
            ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'email' => $user->email
                ],
                'success' => true,
            ]);

        $this->assertDatabaseHas('emails', [
            'subject' => 'Recuperar clave'
        ]);
    }
}
