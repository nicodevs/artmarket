<?php

namespace Tests\Feature;

use App\User;
use App\Email;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_admin_can_list_emails()
    {
        factory(Email::class, 10)->create();
        $this->actingAsAdmin()
            ->json('GET', 'api/emails')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'subject',
                        'tries',
                        'sent'
                    ]
                ]
            ]);
    }

    public function test_an_admin_can_see_an_email()
    {
        factory(Email::class, 10)->create();
        $this->actingAsAdmin()
            ->json('GET', 'api/emails/1')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'subject',
                    'tries',
                    'sent'
                ]
            ]);
    }

    public function test_a_guest_can_not_list_emails()
    {
        factory(Email::class, 10)->create();
        $this->actingAsUser(factory(User::class)->create())
            ->json('GET', 'api/emails')
            ->assertStatus(401);
    }

    public function test_a_guest_can_not_see_an_email()
    {
        factory(Email::class, 10)->create();
        $this->actingAsUser(factory(User::class)->create())
            ->json('GET', 'api/emails/1')
            ->assertStatus(401);
    }
}
