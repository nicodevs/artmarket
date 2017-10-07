<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class SessionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $user = factory(User::class)->make();

        $response = $this->json('POST', 'api/auth/signup', [
            'name' => 'Test User',
            'email' => $user->email,
            'password' => 'password'
        ]);

        $this->user = json_decode($response->getContent())->data;
    }

    public function test_a_user_can_login()
    {
        $this->json('POST', 'api/auth/login', [
                'email' => $this->user->email,
                'password' => 'password'
            ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'email' => $this->user->email
                ],
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => ['token']
            ]);
    }

    public function test_a_user_can_check_his_session()
    {
        $this->json('GET', 'api/auth/whoami?token=' . $this->user->token)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'email' => $this->user->email
                ],
                'success' => true,
            ]);
    }
}
