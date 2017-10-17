<?php

namespace Tests\Feature;

use App\Contest;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ContestsTest extends TestCase
{
    use RefreshDatabase;

    private function createContest()
    {
        Storage::fake('uploads');

        $contest = factory(Contest::class)->make();

        $response = $this->actingAsAdmin()->json('POST', 'api/contests', [
            'title' => $contest->title,
            'description' => $contest->description,
            'terms' => $contest->terms,
            'expires_at' => $contest->expires_at,
            'cover' => UploadedFile::fake()->image('desktop.jpg', 300, 300)->size(1000),
            'prize_image_desktop' => UploadedFile::fake()->image('desktop.jpg', 300, 300)->size(1000),
            'prize_image_mobile' => UploadedFile::fake()->image('desktop.jpg', 300, 300)->size(1000),
            'winners_image_desktop' => UploadedFile::fake()->image('desktop.jpg', 300, 300)->size(1000),
            'winners_image_mobile' => UploadedFile::fake()->image('desktop.jpg', 300, 300)->size(1000)
        ]);

        $response->assertStatus(201);

        $this->contest = json_decode($response->getContent())->data;

        Storage::disk('uploads')->assertExists('contests/' . $this->contest->cover);
        Storage::disk('uploads')->assertExists('contests/' . $this->contest->prize_image_desktop);
        Storage::disk('uploads')->assertExists('contests/' . $this->contest->prize_image_mobile);
        Storage::disk('uploads')->assertExists('contests/' . $this->contest->winners_image_desktop);
        Storage::disk('uploads')->assertExists('contests/' . $this->contest->winners_image_mobile);

        return $response;
    }

    public function test_an_admin_can_create_a_contest()
    {
        $this->createContest()
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => $this->contest->title,
                    'slug' => str_slug($this->contest->title, '-'),
                    'description' => $this->contest->description,
                    'terms' => $this->contest->terms,
                    'cover' => $this->contest->cover,
                    'prize_image_desktop' => $this->contest->prize_image_desktop,
                    'prize_image_mobile' => $this->contest->prize_image_mobile,
                    'winners_image_desktop' => $this->contest->winners_image_desktop,
                    'winners_image_mobile' => $this->contest->winners_image_mobile,
                    'expires_at' => $this->contest->expires_at
                ],
                'success' => true
            ]);
    }

    public function test_a_contest_has_required_fields()
    {
        $this->actingAsAdmin()
            ->json('POST', 'api/contests')
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'invalid_fields'
            ]);
    }

    public function test_the_contest_list_can_be_accessed()
    {
        $contest = factory(Contest::class, 10)->create();

        $this->json('GET', 'api/contests/')
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'slug',
                        'description',
                        'cover',
                        'prize_image_desktop',
                        'prize_image_mobile',
                        'winners_image_desktop',
                        'winners_image_mobile',
                        'expires_at'
                    ]
                ]
            ]);
    }

    public function test_a_contest_can_be_accessed()
    {
        $contest = factory(Contest::class)->create();

        $this->json('GET', 'api/contests/' . $contest->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $contest->id,
                    'title' => $contest->title
                ]
            ]);
    }

    public function test_an_admin_can_edit_a_contest()
    {
        $this->createContest();

        $this->actingAsAdmin()
            ->json('PUT', 'api/contests/' . $this->contest->id, [
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

    public function test_an_admin_can_delete_a_contest()
    {
        $this->createContest();

        $this->actingAsAdmin()
            ->json('DELETE', 'api/contests/' . $this->contest->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_an_unexisting_contest_can_not_be_reached()
    {
        $response = $this->json('GET', 'api/contests/99');

        $response->assertStatus(404);

        $response = $this->json('PUT', 'api/contests/99');

        $response->assertStatus(404);

        $response = $this->json('DELETE', 'api/contests/99');

        $response->assertStatus(404);
    }

    public function test_a_user_can_not_create_contests()
    {
        $this->actingAs(factory(User::class, 'user')->create())
            ->json('POST', 'api/contests', [
                'title' => 'User contest'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_edit_contests()
    {
        $contest = factory(Contest::class)->create();

        $this->actingAs(factory(User::class, 'user')->create())
            ->json('PUT', 'api/contests/' . $contest->id, [
                'title' => 'Updated title'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_delete_contests()
    {
        $contest = factory(Contest::class)->create();

        $this->actingAs(factory(User::class, 'user')->create())
            ->json('DELETE', 'api/contests/' . $contest->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_create_contests()
    {
        $this->json('POST', 'api/contests', [
                'title' => 'Guest contest'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_edit_contests()
    {
        $contest = factory(Contest::class)->create();

        $this->json('PUT', 'api/contests/' . $contest->id, [
                'title' => 'Updated title'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_delete_contests()
    {
        $contest = factory(Contest::class)->create();

        $this->json('DELETE', 'api/contests/' . $contest->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }
}
