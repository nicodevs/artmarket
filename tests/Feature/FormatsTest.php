<?php

namespace Tests\Feature;

use App\Format;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormatsTest extends TestCase
{
    use RefreshDatabase;

    private function createFormat()
    {
        $this->format = factory(Format::class)->make();

        $response = $this->actingAsAdmin()->json('POST', 'api/formats', $this->format->toArray());

        $response->assertStatus(201);

        $this->format = json_decode($response->getContent())->data;

        return $response;
    }

    public function test_an_admin_can_create_a_format()
    {
        $this->createFormat()
            ->assertStatus(201)
            ->assertJson([
                'data' => (array) $this->format,
                'success' => true
            ]);
    }

    public function test_a_format_has_required_fields()
    {
        $this->actingAsAdmin()
            ->json('POST', 'api/formats')
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'invalid_fields'
            ]);
    }

    public function test_the_formats_list_can_be_accessed()
    {
        $formats = factory(Format::class, 10)->create();

        $this->json('GET', 'api/formats/')
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name',
                        'description',
                        'size',
                        'type',
                        'fixed',
                        'enabled',
                        'price',
                        'cost',
                        'frame_price',
                        'frame_cost',
                        'glass_price',
                        'glass_cost',
                        'pack_price',
                        'pack_cost',
                        'side',
                        'minimum_pixels'
                    ]
                ]
            ]);
    }

    public function test_a_format_can_be_accessed()
    {
        $format = factory(Format::class)->create();

        $this->json('GET', 'api/formats/' . $format->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => $format->toArray()
            ]);
    }

    public function test_an_admin_can_edit_a_format()
    {
        $this->createFormat();

        $this->actingAsAdmin()
            ->json('PUT', 'api/formats/' . $this->format->id, [
                'name' => 'New name'
            ])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'New name'
                ]
            ]);
    }

    public function test_an_admin_can_delete_a_format()
    {
        $this->createFormat();

        $this->actingAsAdmin()
            ->json('DELETE', 'api/formats/' . $this->format->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_an_unexisting_format_can_not_be_reached()
    {
        $response = $this->json('GET', 'api/formats/99');

        $response->assertStatus(404);

        $response = $this->json('PUT', 'api/formats/99');

        $response->assertStatus(404);

        $response = $this->json('DELETE', 'api/formats/99');

        $response->assertStatus(404);
    }

    public function test_a_user_can_not_create_formats()
    {
        $this->actingAs(factory(User::class, 'user')->create())
            ->json('POST', 'api/formats', [
                'name' => 'Guest format'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_edit_formats()
    {
        $format = factory(Format::class)->create();

        $this->actingAs(factory(User::class, 'user')->create())
            ->json('PUT', 'api/formats/' . $format->id, [
                'name' => 'Updated name'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_delete_formats()
    {
        $format = factory(Format::class)->create();

        $this->actingAs(factory(User::class, 'user')->create())
            ->json('DELETE', 'api/formats/' . $format->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_create_formats()
    {
        $this->json('POST', 'api/formats', [
                'name' => 'Guest format'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_edit_formats()
    {
        $format = factory(Format::class)->create();

        $this->json('PUT', 'api/formats/' . $format->id, [
                'name' => 'Updated name'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_delete_formats()
    {
        $format = factory(Format::class)->create();

        $this->json('DELETE', 'api/formats/' . $format->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }
}
