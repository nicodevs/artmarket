<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use App\Comment;
use App\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->prepareForTests();
    }

    private function prepareForTests()
    {
        $user = factory(User::class)->make();

        $response = $this->json('POST', 'api/auth/signup', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password'
        ]);

        $this->user = json_decode($response->getContent())->data;
    }

    private function createCategory()
    {
        $this->category = factory(Category::class)->make();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->token
        ])->json('POST', 'api/categories', [
            'name' => $this->category->name
        ]);

        $this->category = json_decode($response->getContent())->data;

        return $response;
    }

    public function test_a_registered_user_can_create_a_category()
    {
        $response = $this->createCategory();

        $response
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => $this->category->name
                ],
                'success' => true
            ]);
    }

    public function test_a_category_has_required_fields()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->token
        ])->json('POST', 'api/categories');

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'invalid_fields'
            ]);
    }

    public function test_a_category_can_be_accessed()
    {
        $category = factory(Category::class)->create();

        $response = $this->json('GET', 'api/categories/' . $category->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name
                ]
            ]);
    }

    public function test_a_category_can_be_edited()
    {
        $this->createCategory();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->token
        ])->json('PUT', 'api/categories/' . $this->category->id, [
            'name' => 'New name'
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'New name'
                ]
            ]);
    }

    public function test_a_category_can_be_deleted()
    {
        $this->createCategory();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->token
        ])->json('DELETE', 'api/categories/' . $this->category->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_a_guest_can_not_create_comments()
    {
        $response = $this->json('POST', 'api/categories', [
            'name' => 'Guest category'
        ]);

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_an_unexisting_comment_can_not_be_reached()
    {
        $response = $this->json('GET', 'api/categories/99');

        $response->assertStatus(404);

        $response = $this->json('PUT', 'api/categories/99');

        $response->assertStatus(404);

        $response = $this->json('DELETE', 'api/categories/99');

        $response->assertStatus(404);
    }

    public function test_a_guest_can_not_edit_categories()
    {
        $category = factory(Category::class)->create();

        $response = $this->json('PUT', 'api/categories/' . $category->id, [
            'name' => 'Updated name'
        ]);

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_delete_categories()
    {
        $category = factory(Category::class)->create();

        $response = $this->json('DELETE', 'api/categories/' . $category->id);

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }
}
