<?php

namespace Tests\Feature;

use App\Slide;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SlidesTest extends TestCase
{
    use RefreshDatabase;

    private function createSlide()
    {
        Storage::fake('uploads');

        $slide = factory(Slide::class)->make()->toArray();

        $response = $this->actingAsAdmin()->json('POST', 'api/slides', [
            'description' => $slide['description'],
            'sequence' => $slide['sequence'],
            'href' => $slide['href'],
            'desktop' => UploadedFile::fake()->image('desktop.jpg', 300, 300)->size(1000),
            'mobile' => UploadedFile::fake()->image('mobile.jpg', 300, 300)->size(1000)
        ]);

        $response->assertStatus(201);

        $this->slide = json_decode($response->getContent())->data;

        Storage::disk('uploads')->assertExists('slides/' . $this->slide->desktop);
        Storage::disk('uploads')->assertExists('slides/' . $this->slide->mobile);

        return $response;
    }

    public function test_an_admin_can_create_a_slide()
    {
        $this->createSlide()
            ->assertStatus(201)
            ->assertJson([
                'data' =>  [
                    'description' => $this->slide->description,
                    'desktop' => $this->slide->desktop,
                    'mobile' => $this->slide->mobile,
                    'sequence' => $this->slide->sequence
                ],
                'success' => true
            ]);
    }

    public function test_a_slide_has_required_fields()
    {
        $this->actingAsAdmin()
            ->json('POST', 'api/slides')
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'invalid_fields'
            ]);
    }

    public function test_the_slides_list_can_be_accessed()
    {
        $slides = factory(Slide::class, 10)->create();

        $this->json('GET', 'api/slides/')
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'description',
                        'desktop',
                        'mobile',
                        'sequence',
                        'href'
                    ]
                ]
            ]);
    }

    public function test_a_slide_can_be_accessed()
    {
        $slide = factory(Slide::class)->create();

        $this->json('GET', 'api/slides/' . $slide->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => $slide->toArray()
            ]);
    }

    public function test_an_admin_can_edit_a_slide()
    {
        $this->createSlide();

        $this->actingAsAdmin()
            ->json('PUT', 'api/slides/' . $this->slide->id, [
                'description' => 'Baz'
            ])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'description' => 'Baz'
                ]
            ]);
    }

    public function test_an_admin_can_delete_a_slide()
    {
        $this->createSlide();

        $this->actingAsAdmin()
            ->json('DELETE', 'api/slides/' . $this->slide->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_an_unexisting_slide_can_not_be_reached()
    {
        $response = $this->json('GET', 'api/slides/99');

        $response->assertStatus(404);

        $response = $this->json('PUT', 'api/slides/99');

        $response->assertStatus(404);

        $response = $this->json('DELETE', 'api/slides/99');

        $response->assertStatus(404);
    }

    public function test_a_user_can_not_create_slides()
    {
        $this->actingAs(factory(User::class, 'user')->create())
            ->json('POST', 'api/slides', [
                'description' => 'Bar'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_edit_slides()
    {
        $slide = factory(Slide::class)->create();

        $this->actingAs(factory(User::class, 'user')->create())
            ->json('PUT', 'api/slides/' . $slide->id, [
                'description' => 'Baz'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_delete_slides()
    {
        $slide = factory(Slide::class)->create();

        $this->actingAs(factory(User::class, 'user')->create())
            ->json('DELETE', 'api/slides/' . $slide->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_create_slides()
    {
        $this->json('POST', 'api/slides', [
                'description' => 'Bar'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_edit_slides()
    {
        $slide = factory(Slide::class)->create();

        $this->json('PUT', 'api/slides/' . $slide->id, [
                'description' => 'Bar'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_delete_slides()
    {
        $slide = factory(Slide::class)->create();

        $this->json('DELETE', 'api/slides/' . $slide->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }
}
