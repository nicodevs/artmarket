<?php

namespace Tests\Feature;

use App\Frame;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FramesTest extends TestCase
{
    use RefreshDatabase;

    private function createFrame()
    {
        $this->frame = factory(Frame::class)->make();

        $response = $this->actingAsAdmin()->json('POST', 'api/frames', $this->frame->toArray());

        $response->assertStatus(201);

        $this->frame = json_decode($response->getContent())->data;

        return $response;
    }

    public function test_an_admin_can_create_a_frame()
    {
        $this->createFrame()
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => $this->frame->name
                ],
                'success' => true
            ]);
    }

    public function test_a_frame_has_required_fields()
    {
        $this->actingAsAdmin()
            ->json('POST', 'api/frames')
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'invalid_fields'
            ]);
    }

    public function test_the_frames_list_can_be_accessed()
    {
        $frames = factory(Frame::class, 10)->create();

        $this->json('GET', 'api/frames/')
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name',
                        'border',
                        'thumbnail'
                    ]
                ]
            ]);
    }

    public function test_a_frame_can_be_accessed()
    {
        $frame = factory(Frame::class)->create();

        $this->json('GET', 'api/frames/' . $frame->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $frame->id,
                    'name' => $frame->name,
                    'border' => $frame->border,
                    'thumbnail' => $frame->thumbnail
                ]
            ]);
    }

    public function test_an_admin_can_edit_a_frame()
    {
        $this->createFrame();

        $this->actingAsAdmin()
            ->json('PUT', 'api/frames/' . $this->frame->id, [
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

    public function test_an_admin_can_delete_a_frame()
    {
        $this->createFrame();

        $this->actingAsAdmin()
            ->json('DELETE', 'api/frames/' . $this->frame->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_an_unexisting_frame_can_not_be_reached()
    {
        $response = $this->json('GET', 'api/frames/99');

        $response->assertStatus(404);

        $response = $this->json('PUT', 'api/frames/99');

        $response->assertStatus(404);

        $response = $this->json('DELETE', 'api/frames/99');

        $response->assertStatus(404);
    }

    public function test_a_user_can_not_create_frames()
    {
        $this->actingAs(factory(User::class, 'user')->create())
            ->json('POST', 'api/frames', [
                'name' => 'Guest frame'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_edit_frames()
    {
        $frame = factory(Frame::class)->create();

        $this->actingAs(factory(User::class, 'user')->create())
            ->json('PUT', 'api/frames/' . $frame->id, [
                'name' => 'Updated name'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_delete_frames()
    {
        $frame = factory(Frame::class)->create();

        $this->actingAs(factory(User::class, 'user')->create())
            ->json('DELETE', 'api/frames/' . $frame->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_create_frames()
    {
        $this->json('POST', 'api/frames', [
                'name' => 'Guest frame'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_edit_frames()
    {
        $frame = factory(Frame::class)->create();

        $this->json('PUT', 'api/frames/' . $frame->id, [
                'name' => 'Updated name'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_delete_frames()
    {
        $frame = factory(Frame::class)->create();

        $this->json('DELETE', 'api/frames/' . $frame->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }
}
