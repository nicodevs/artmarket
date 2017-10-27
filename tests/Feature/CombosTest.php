<?php

namespace Tests\Feature;

use App\Combo;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CombosTest extends TestCase
{
    use RefreshDatabase;

    private function createCombo()
    {
        Storage::fake('uploads');

        $combo = factory(Combo::class)->make();

        $response = $this->actingAsAdmin()->json('POST', 'api/combos', [
            'name' => $combo->name,
            'description' => $combo->description,
            'thumbnail' => UploadedFile::fake()->image('desktop.jpg', 50, 50)->size(200),
            'thumbnail_mobile' => UploadedFile::fake()->image('desktop.jpg', 50, 50)->size(200),
            'cart' => $combo->cart
        ]);

        $response->assertStatus(201);

        $this->combo = json_decode($response->getContent())->data;

        Storage::disk('uploads')->assertExists('combos/' . $this->combo->thumbnail);
        Storage::disk('uploads')->assertExists('combos/' . $this->combo->thumbnail_mobile);

        return $response;
    }

    public function test_an_admin_can_create_a_combo()
    {
        $this->createCombo()
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => $this->combo->name
                ],
                'success' => true
            ]);
    }

    public function test_a_combo_has_required_fields()
    {
        $this->actingAsAdmin()
            ->json('POST', 'api/combos')
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'invalid_fields'
            ]);
    }

    public function test_an_admin_can_list_combos()
    {
        $combos = factory(Combo::class, 10)->create();

        $this->actingAsAdmin()
            ->json('GET', 'api/combos/')
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name',
                        'description',
                        'thumbnail',
                        'thumbnail_mobile',
                        'cart'
                    ]
                ]
            ]);
    }

    public function test_a_combo_can_be_publicly_accessed()
    {
        $combo = factory(Combo::class)->create();

        $this->json('GET', 'api/combos/' . $combo->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $combo->id,
                    'name' => $combo->name,
                    'thumbnail' => $combo->thumbnail,
                    'thumbnail_mobile' => $combo->thumbnail_mobile,
                    'cart' => $combo->cart
                ]
            ]);
    }

    public function test_an_admin_can_edit_a_combo()
    {
        $this->createCombo();

        $this->actingAsAdmin()
            ->json('PUT', 'api/combos/' . $this->combo->id, [
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

    public function test_an_admin_can_delete_a_combo()
    {
        $this->createCombo();

        $this->actingAsAdmin()
            ->json('DELETE', 'api/combos/' . $this->combo->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_an_unexisting_combo_can_not_be_reached()
    {
        $response = $this->json('GET', 'api/combos/99');

        $response->assertStatus(404);

        $response = $this->json('PUT', 'api/combos/99');

        $response->assertStatus(404);

        $response = $this->json('DELETE', 'api/combos/99');

        $response->assertStatus(404);
    }

    public function test_a_user_can_not_create_combos()
    {
        $this->actingAs(factory(User::class, 'user')->create())
            ->json('POST', 'api/combos', [
                'name' => 'User combo'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_edit_combos()
    {
        $combo = factory(Combo::class)->create();

        $this->actingAs(factory(User::class, 'user')->create())
            ->json('PUT', 'api/combos/' . $combo->id, [
                'name' => 'Updated name'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_delete_combos()
    {
        $combo = factory(Combo::class)->create();

        $this->actingAs(factory(User::class, 'user')->create())
            ->json('DELETE', 'api/combos/' . $combo->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_create_combos()
    {
        $this->json('POST', 'api/combos', [
                'name' => 'Guest combo'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_edit_combos()
    {
        $combo = factory(Combo::class)->create();

        $this->json('PUT', 'api/combos/' . $combo->id, [
                'name' => 'Updated name'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_delete_combos()
    {
        $combo = factory(Combo::class)->create();

        $this->json('DELETE', 'api/combos/' . $combo->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }
}
