<?php

namespace Tests\Feature;

use App\Content;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    private function createContent()
    {
        $this->content = factory(Content::class)->make();

        $response = $this->actingAsAdmin()->json('POST', 'api/contents', [
            'title' => $this->content->title,
            'content' => $this->content->content
        ]);

        $response->assertStatus(201);

        $this->content = json_decode($response->getContent())->data;

        return $response;
    }

    public function test_an_admin_can_create_a_content()
    {
        $this->createContent()
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => $this->content->title,
                    'slug' => str_slug($this->content->title, '-')
                ],
                'success' => true
            ]);
    }

    public function test_a_content_has_required_fields()
    {
        $this->actingAsAdmin()
            ->json('POST', 'api/contents')
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'invalid_fields'
            ]);
    }

    public function test_a_content_can_be_accessed()
    {
        $content = factory(Content::class)->create();

        $this->json('GET', 'api/contents/' . $content->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $content->id,
                    'title' => $content->title
                ]
            ]);
    }

    public function test_an_admin_can_edit_a_content()
    {
        $this->createContent();

        $this->actingAsAdmin()
            ->json('PUT', 'api/contents/' . $this->content->id, [
                'title' => 'New title'
            ])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => 'New title',
                    'slug' => 'new-title'
                ]
            ]);
    }

    public function test_an_admin_can_delete_a_content()
    {
        $this->createContent();

        $this->actingAsAdmin()
            ->json('DELETE', 'api/contents/' . $this->content->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_an_unexisting_content_can_not_be_reached()
    {
        $response = $this->json('GET', 'api/contents/99');

        $response->assertStatus(404);

        $response = $this->json('PUT', 'api/contents/99');

        $response->assertStatus(404);

        $response = $this->json('DELETE', 'api/contents/99');

        $response->assertStatus(404);
    }

    public function test_a_user_can_not_create_contents()
    {
        $this->actingAs(factory(User::class, 'user')->create())
            ->json('POST', 'api/contents', [
                'title' => 'User content'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_edit_contents()
    {
        $content = factory(Content::class)->create();

        $this->actingAs(factory(User::class, 'user')->create())
            ->json('PUT', 'api/contents/' . $content->id, [
                'title' => 'Updated title'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_delete_contents()
    {
        $content = factory(Content::class)->create();

        $this->actingAs(factory(User::class, 'user')->create())
            ->json('DELETE', 'api/contents/' . $content->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_create_contents()
    {
        $this->json('POST', 'api/contents', [
                'title' => 'Guest content'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_edit_contents()
    {
        $content = factory(Content::class)->create();

        $this->json('PUT', 'api/contents/' . $content->id, [
                'title' => 'Updated title'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_delete_contents()
    {
        $content = factory(Content::class)->create();

        $this->json('DELETE', 'api/contents/' . $content->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }
}
