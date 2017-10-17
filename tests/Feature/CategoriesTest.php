<?php

namespace Tests\Feature;

use App\Category;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    private function createCategory()
    {
        Storage::fake('uploads');

        $this->category = factory(Category::class)->make();

        $response = $this->actingAsAdmin()->json('POST', 'api/categories', [
            'name' => $this->category->name,
            'cover' => UploadedFile::fake()->image('cover.jpg', 200, 200)->size(1000),
            'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg', 200, 200)->size(1000)
        ]);

        $response->assertStatus(201);

        $this->category = json_decode($response->getContent())->data;

        Storage::disk('uploads')->assertExists('categories/' . $this->category->cover);
        Storage::disk('uploads')->assertExists('categories/' . $this->category->thumbnail);

        return $response;
    }

    public function test_an_admin_can_create_a_category()
    {
        $this->createCategory()
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => $this->category->name
                ],
                'success' => true
            ])->assertJsonStructure([
                'data' => ['name', 'thumbnail', 'cover']
            ]);
    }

    public function test_a_category_has_required_fields()
    {
        $this->actingAsAdmin()
            ->json('POST', 'api/categories')
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'invalid_fields'
            ]);
    }

    public function test_the_categories_list_can_be_accessed()
    {
        $categories = factory(Category::class, 10)->create();

        $this->json('GET', 'api/categories/')
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name',
                        'cover',
                        'thumbnail'
                    ]
                ]
            ]);
    }

    public function test_a_category_can_be_accessed()
    {
        $category = factory(Category::class)->create();

        $this->json('GET', 'api/categories/' . $category->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'cover' => $category->cover,
                    'thumbnail' => $category->thumbnail
                ]
            ]);
    }

    public function test_an_admin_can_edit_a_category()
    {
        $this->createCategory();

        $this->actingAsAdmin()
            ->json('PUT', 'api/categories/' . $this->category->id, [
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

    public function test_an_admin_can_delete_a_category()
    {
        $this->createCategory();

        $this->actingAsAdmin()
            ->json('DELETE', 'api/categories/' . $this->category->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_an_unexisting_category_can_not_be_reached()
    {
        $response = $this->json('GET', 'api/categories/99');

        $response->assertStatus(404);

        $response = $this->json('PUT', 'api/categories/99');

        $response->assertStatus(404);

        $response = $this->json('DELETE', 'api/categories/99');

        $response->assertStatus(404);
    }

    public function test_a_user_can_not_create_categories()
    {
        $this->actingAs(factory(User::class, 'user')->create())
            ->json('POST', 'api/categories', [
                'name' => 'Guest category'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_edit_categories()
    {
        $category = factory(Category::class)->create();

        $this->actingAs(factory(User::class, 'user')->create())
            ->json('PUT', 'api/categories/' . $category->id, [
                'name' => 'Updated name'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_delete_categories()
    {
        $category = factory(Category::class)->create();

        $this->actingAs(factory(User::class, 'user')->create())
            ->json('DELETE', 'api/categories/' . $category->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_create_categories()
    {
        $this->json('POST', 'api/categories', [
                'name' => 'Guest category'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_edit_categories()
    {
        $category = factory(Category::class)->create();

        $this->json('PUT', 'api/categories/' . $category->id, [
                'name' => 'Updated name'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_delete_categories()
    {
        $category = factory(Category::class)->create();

        $this->json('DELETE', 'api/categories/' . $category->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }
}
