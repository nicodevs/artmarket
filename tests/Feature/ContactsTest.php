<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_can_send_a_contact_message()
    {
        $this->json('POST', 'api/contact', ['message' => 'Foo', 'email' => 'test@example.com'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseHas('emails', [
            'subject' => 'Mensaje de test@example.com desde el sitio web'
        ]);
    }
}
