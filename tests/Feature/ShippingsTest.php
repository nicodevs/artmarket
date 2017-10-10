<?php

namespace Tests\Feature;

use App\Shipping;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShippingsTest extends TestCase
{
    use RefreshDatabase;

    private function createShipping()
    {
        $this->shipping = factory(Shipping::class)->make();

        $response = $this->actingAsAdmin()->json('POST', 'api/shippings', $this->shipping->toArray());

        $response->assertStatus(201);

        $this->shipping = json_decode($response->getContent())->data;

        return $response;
    }

    public function test_an_admin_can_create_a_shipping()
    {
        $this->createShipping()
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => $this->shipping->name,
                    'description' => $this->shipping->description,
                    'price' => $this->shipping->price,
                    'pickup' => $this->shipping->pickup,
                    'enabled' => $this->shipping->enabled
                ],
                'success' => true
            ]);
    }

    public function test_a_shipping_has_required_fields()
    {
        $this->actingAsAdmin()
            ->json('POST', 'api/shippings')
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'invalid_fields'
            ]);
    }

    public function test_the_shippings_list_can_be_accessed()
    {
        $shippings = factory(Shipping::class, 10)->create();

        $this->json('GET', 'api/shippings/')
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name',
                        'description',
                        'price',
                        'pickup',
                        'enabled'
                    ]
                ]
            ]);
    }

    public function test_a_shipping_can_be_accessed()
    {
        $shipping = factory(Shipping::class)->create();

        $this->json('GET', 'api/shippings/' . $shipping->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $shipping->id,
                    'name' => $shipping->name,
                    'description' => $shipping->description,
                    'price' => $shipping->price,
                    'pickup' => $shipping->pickup,
                    'enabled' => $shipping->enabled
                ]
            ]);
    }

    public function test_an_admin_can_edit_a_shipping()
    {
        $this->createShipping();

        $this->actingAsAdmin()
            ->json('PUT', 'api/shippings/' . $this->shipping->id, [
                'name' => 'Bar',
                'enabled' => false,
                'pickup' => false
            ])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'Bar',
                    'enabled' => false,
                    'pickup' => false
                ]
            ]);
    }

    public function test_an_admin_can_delete_a_shipping()
    {
        $this->createShipping();

        $this->actingAsAdmin()
            ->json('DELETE', 'api/shippings/' . $this->shipping->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_an_unexisting_shipping_can_not_be_reached()
    {
        $response = $this->json('GET', 'api/shippings/99');

        $response->assertStatus(404);

        $response = $this->json('PUT', 'api/shippings/99');

        $response->assertStatus(404);

        $response = $this->json('DELETE', 'api/shippings/99');

        $response->assertStatus(404);
    }

    public function test_a_user_can_not_create_shippings()
    {
        $this->actingAs(factory(User::class, 'user')->create())
            ->json('POST', 'api/shippings', [
                'name' => 'Bar'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_edit_shippings()
    {
        $shipping = factory(Shipping::class)->create();

        $this->actingAs(factory(User::class, 'user')->create())
            ->json('PUT', 'api/shippings/' . $shipping->id, [
                'name' => 'Baz'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_delete_shippings()
    {
        $shipping = factory(Shipping::class)->create();

        $this->actingAs(factory(User::class, 'user')->create())
            ->json('DELETE', 'api/shippings/' . $shipping->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_create_shippings()
    {
        $this->json('POST', 'api/shippings', [
                'name' => 'Bar'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_edit_shippings()
    {
        $shipping = factory(Shipping::class)->create();

        $this->json('PUT', 'api/shippings/' . $shipping->id, [
                'name' => 'Bar'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_delete_shippings()
    {
        $shipping = factory(Shipping::class)->create();

        $this->json('DELETE', 'api/shippings/' . $shipping->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }
}
