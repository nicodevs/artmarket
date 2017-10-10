<?php

namespace Tests\Feature;

use App\Coupon;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponsTest extends TestCase
{
    use RefreshDatabase;

    private function createCoupon()
    {
        $this->coupon = factory(Coupon::class)->make();

        $response = $this->actingAsAdmin()->json('POST', 'api/coupons', [
            'name' => $this->coupon->name,
            'discount' => $this->coupon->discount,
            'max_uses' => $this->coupon->max_uses,
            'expires_at' => $this->coupon->expires_at
        ]);

        //dd($response->getContent());

        $response->assertStatus(201);
        $this->coupon = json_decode($response->getContent())->data;

        return $response;
    }

    public function test_an_admin_can_create_a_coupon()
    {
        $this->createCoupon()
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => $this->coupon->name,
                    'expires_at' => $this->coupon->expires_at
                ],
                'success' => true
            ]);
    }

    public function test_a_coupon_has_required_fields()
    {
        $this->actingAsAdmin()
            ->json('POST', 'api/coupons')
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'invalid_fields'
            ]);
    }

    public function test_admins_can_list_coupons()
    {
        $coupon = factory(Coupon::class, 10)->create();

        $this->actingAsAdmin()
            ->json('GET', 'api/coupons/')
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'discount',
                        'description',
                        'expires_at'
                    ]
                ]
            ]);
    }

    public function test_guests_cant_list_coupons()
    {
        $coupon = factory(Coupon::class, 10)->create();

        $this->json('GET', 'api/coupons/')
            ->assertStatus(401);
    }

    public function test_users_cant_list_coupons()
    {
        $coupon = factory(Coupon::class, 10)->create();

        $this->actingAs(factory(User::class)->create())
            ->json('GET', 'api/coupons/')
            ->assertStatus(401);
    }

    public function test_a_coupon_can_be_accessed()
    {
        $coupon = factory(Coupon::class)->create();

        $this->json('GET', 'api/coupons/' . $coupon->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $coupon->id,
                    'name' => $coupon->name
                ]
            ]);
    }

    public function test_an_admin_can_edit_a_coupon()
    {
        $this->createCoupon();

        $this->actingAsAdmin()
            ->json('PUT', 'api/coupons/' . $this->coupon->id, [
                'name' => 'Updated'
            ])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'Updated'
                ]
            ]);
    }

    public function test_an_admin_can_delete_a_coupon()
    {
        $this->createCoupon();

        $this->actingAsAdmin()
            ->json('DELETE', 'api/coupons/' . $this->coupon->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_an_unexisting_coupon_can_not_be_reached()
    {
        $response = $this->json('GET', 'api/coupons/99');

        $response->assertStatus(404);

        $response = $this->json('PUT', 'api/coupons/99');

        $response->assertStatus(404);

        $response = $this->json('DELETE', 'api/coupons/99');

        $response->assertStatus(404);
    }

    public function test_a_user_can_not_create_coupons()
    {
        $this->actingAs(factory(User::class, 'user')->create())
            ->json('POST', 'api/coupons', [
                'name' => 'Foo'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_edit_coupons()
    {
        $coupon = factory(Coupon::class)->create();

        $this->actingAs(factory(User::class, 'user')->create())
            ->json('PUT', 'api/coupons/' . $coupon->id, [
                'name' => 'Foo'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_delete_coupons()
    {
        $coupon = factory(Coupon::class)->create();

        $this->actingAs(factory(User::class, 'user')->create())
            ->json('DELETE', 'api/coupons/' . $coupon->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_create_coupons()
    {
        $this->json('POST', 'api/coupons', [
                'name' => 'Foo'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_edit_coupons()
    {
        $coupon = factory(Coupon::class)->create();

        $this->json('PUT', 'api/coupons/' . $coupon->id, [
                'name' => 'Foo'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_delete_coupons()
    {
        $coupon = factory(Coupon::class)->create();

        $this->json('DELETE', 'api/coupons/' . $coupon->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }
}
