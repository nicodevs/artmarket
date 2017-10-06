<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register()
    {
        $user = factory(User::class)->make();

        $response = $this->json('POST', 'api/auth/signup', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email
                ],
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => ['token']
            ]);
    }
}
