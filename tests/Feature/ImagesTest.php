<?php

namespace Tests\Feature;

use App\Image;
use App\User;
use App\Contest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_create_an_image()
    {
        $image = factory(Image::class)->make();
        $user = factory(User::class)->create();
        $data = $image->toArray();

        $response = $this->actingAsUser($user)
            ->json('POST', 'api/images', $data);

        //dd($response->getContent());

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => $image->name
                ]
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'url',
                    'tags',
                    'user_id',
                    'updated_at',
                    'created_at',
                    'categories' => [
                        '*' => [
                            'id', 'name'
                        ]
                    ]
                ]
            ]);
    }

    public function test_an_image_has_required_fields()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAsUser($user)
            ->json('POST', 'api/images', [
                'name' => 'Foo'
            ])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'invalid_fields'
            ]);
    }

    public function test_a_user_can_edit_an_image()
    {
        $image = factory(Image::class)->make();
        $user = factory(User::class)->create();
        $created = $user->images()->create($image->toArray());

        $data = factory(Image::class, 'user_edit')->make()->toArray();

        $response = $this->actingAsUser($user)
            ->json('PUT', 'api/images/' . $created->id, $data)
            ->assertStatus(200)
            ->assertJson([
                'data' => $data,
                'success' => true
            ]);
    }

    public function test_a_user_can_delete_an_image()
    {
        $image = factory(Image::class)->make();
        $user = factory(User::class)->create();
        $created = $user->images()->create($image->toArray());

        $response = $this->actingAsUser($user)
            ->json('DELETE', 'api/images/' . $created->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_a_user_can_not_edit_an_image_he_does_not_own()
    {
        $image = factory(Image::class)->make();
        $owner = factory(User::class)->create();
        $created = $owner->images()->create($image->toArray());

        $user = factory(User::class)->create();

        $response = $this->actingAsUser($user)
            ->json('PUT', 'api/images/' . $created->id, [
                'name' => 'Foo'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_delete_an_image_he_does_not_own()
    {
        $image = factory(Image::class)->make();
        $owner = factory(User::class)->create();
        $created = $owner->images()->create($image->toArray());

        $user = factory(User::class)->create();

        $response = $this->actingAsUser($user)
            ->json('DELETE', 'api/images/' . $created->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_an_admin_can_edit_an_image()
    {
        $image = factory(Image::class)->make();
        $user = factory(User::class)->create();
        $created = $user->images()->create($image->toArray());

        $data = factory(Image::class, 'admin_edit')->make()->toArray();

        $response = $this->actingAsAdmin()
            ->json('PUT', 'api/images/' . $created->id, $data)
            ->assertStatus(200)
            ->assertJson([
                'data' => $data,
                'success' => true
            ]);
    }

    public function test_an_admin_can_delete_an_image()
    {
        $image = factory(Image::class)->make();
        $user = factory(User::class)->create();
        $created = $user->images()->create($image->toArray());

        $response = $this->actingAsAdmin()
            ->json('DELETE', 'api/images/' . $created->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_a_user_can_not_edit_reserved_fields()
    {
        $image = factory(Image::class)->make();
        $user = factory(User::class)->create();
        $created = $user->images()->create($image->toArray());

        $created->status = 'BANNED';
        $created->visibility = 'PROFILE';
        $created->url = 'Bar';
        $created->save();

        $response = $this->actingAsUser($user)
            ->json('PUT', 'api/images/' . $created->id, [
                'status' => 'APPROVED',
                'visibility' => 'ALL',
                'url_cutoff' => 'Foo'
            ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'status' => 'BANNED',
                    'visibility' => 'PROFILE',
                    'url' => $created->url,
                    'url_cutoff' => ''
                ],
                'success' => true
            ]);
    }

    public function test_a_guest_can_not_create_images()
    {
        $this->json('POST', 'api/images', [
                'name' => 'Guest image'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_edit_images()
    {
        $image = factory(Image::class)->make();
        $user = factory(User::class)->create();
        $created = $user->images()->create($image->toArray());

        $this->json('PUT', 'api/images/' . $created->id, [
                'name' => 'Updated name'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_delete_images()
    {
        $image = factory(Image::class)->make();
        $user = factory(User::class)->create();
        $created = $user->images()->create($image->toArray());

        $this->json('DELETE', 'api/images/' . $created->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_an_image_can_be_publicly_accessed()
    {
        $image = factory(Image::class, 'whitout_categories')->create();

        $response = $this->json('GET', 'api/images/' . $image->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => $image->toArray()
            ]);
    }

    public function test_the_image_list_can_be_publicly_accessed()
    {
        $image = factory(Image::class, 'whitout_categories', 10)->create();

        $this->json('GET', 'api/images/')
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'user_id',
                        'contest_id',
                        'tags',
                        'url',
                        'url_disk',
                        'url_cutoff',
                        'gravity',
                        'status',
                        'visibility',
                        'featured',
                        'sales',
                        'visits',
                        'width',
                        'width_disk',
                        'height_disk',
                        'width_cutoff',
                        'height_cutoff',
                        'height',
                        'random',
                        'extra',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    public function test_an_unexisting_image_can_not_be_reached()
    {
        $response = $this->json('GET', 'api/images/99');
        $response->assertStatus(404);

        $response = $this->json('PUT', 'api/images/99');
        $response->assertStatus(404);

        $response = $this->json('DELETE', 'api/images/99');
        $response->assertStatus(404);
    }

    public function test_an_image_can_not_participate_in_an_invalid_contest()
    {
        $image = factory(Image::class)->make();
        $user = factory(User::class)->create();
        $created = $user->images()->create($image->toArray());

        $response = $this->actingAsUser($user)
            ->json('PUT', 'api/images/' . $created->id, [
                'contest_id' => 1
            ])
            ->assertStatus(422)
            ->assertJson([
                'success' => false
            ])
            ->assertJsonStructure([
                'errors' => [
                    'contest_id'
                ]
            ]);
    }

    public function test_an_image_can_participate_in_an_valid_contest()
    {
        $image = factory(Image::class)->make();
        $user = factory(User::class)->create();
        $contest = factory(Contest::class)->create();
        $created = $user->images()->create($image->toArray());

        $response = $this->actingAsUser($user)
            ->json('PUT', 'api/images/' . $created->id, [
                'contest_id' => $contest->id
            ])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'contest_id' => $contest->id
                ]
            ]);
    }
}
