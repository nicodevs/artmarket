<?php

namespace Tests\Feature;

use App\Image;
use App\User;
use App\Contest;
use App\Events\ImageApproved;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Event;

class ImagesTest extends TestCase
{
    use RefreshDatabase;

    private function uploadFile()
    {
        $this->user = factory(User::class)->create();

        Storage::fake('uploads');

        $response = $this->actingAsUser($this->user)
            ->json('POST', 'api/images/files', [
                'image' => UploadedFile::fake()->image('photo.jpg', 2000, 3000)->size(1500)
            ])
            ->assertStatus(201);

        $this->fakeFile = json_decode($response->getContent())->data;
    }

    private function getUser()
    {
        return $this->user;
    }

    private function getFakeFile()
    {
        return $this->fakeFile;
    }

    public function test_a_user_can_create_an_image()
    {
        $this->uploadFile();
        $image = factory(Image::class)->make(['url' => $this->getFakeFile()->filename]);
        $data = $image->toArray();

        $response = $this->actingAsUser($this->getUser())
            ->json('POST', 'api/images', $data)
            ->assertStatus(201)
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

    public function test_a_user_can_set_the_disc_attributes()
    {
        $this->uploadFile();
        $image = factory(Image::class)->make([
            'url' => $this->getFakeFile()->filename,
            'url_disc' => 'AUTO',
            'gravity' => 'North'
        ]);
        $data = $image->toArray();

        $response = $this->actingAsUser($this->getUser())
            ->json('POST', 'api/images', $data)
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => $image->name,
                    'url_disc' => 'AUTO',
                    'gravity' => 'North'
                ]
            ]);
    }

    public function test_an_admin_can_set_a_custom_disc_image()
    {
        Storage::fake('uploads');

        $user = factory(User::class)->create();

        $response = $this->actingAsUser($user)
            ->json('POST', 'api/images/files', [
                'image' => UploadedFile::fake()->image('frame.jpg', 2000, 3000)->size(1500)
            ])
            ->assertStatus(201);

        $url = json_decode($response->getContent())->data->filename;

        $response = $this->actingAsUser($user)
            ->json('POST', 'api/images/files', [
                'image' => UploadedFile::fake()->image('disc.png', 2000, 2000)->size(1500)
            ])
            ->assertStatus(201);

        $urlDisc = json_decode($response->getContent())->data->filename;

        $image = factory(Image::class)->make([
            'url' => $url,
            'url_disc' => $urlDisc
        ]);
        $data = $image->toArray();

        $response = $this->actingAsUser($user)
            ->json('POST', 'api/images', $data)
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => $image->name,
                    'url' => $image->url,
                    'url_disc' => $image->url_disc,
                    'width' => 2000,
                    'height' => 3000,
                    'width_disc' => 2000,
                    'height_disc' => 2000
                ]
            ]);

        Storage::disk('uploads')->assertExists('originals/' . $image->url);
        Storage::disk('uploads')->assertExists('originals/' . $image->url_disc);
    }

    public function test_an_image_has_required_fields()
    {
        $this->actingAsUser(factory(User::class)->create())
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
        $this->uploadFile();
        $image = factory(Image::class)->make(['url' => $this->getFakeFile()->filename]);
        $user = $this->getUser();
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
        $this->uploadFile();
        $image = factory(Image::class)->make(['url' => $this->getFakeFile()->filename]);
        $user = $this->getUser();
        $created = $user->images()->create($image->toArray());

        $this->actingAsUser($user)
            ->json('DELETE', 'api/images/' . $created->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_a_user_can_not_edit_an_image_he_does_not_own()
    {
        $this->uploadFile();
        $image = factory(Image::class)->make(['url' => $this->getFakeFile()->filename]);
        $owner = factory(User::class)->create();
        $created = $owner->images()->create($image->toArray());

        $illegal = factory(User::class)->create();

        $this->actingAsUser($illegal)
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
        $this->uploadFile();
        $image = factory(Image::class)->make(['url' => $this->getFakeFile()->filename]);
        $owner = factory(User::class)->create();
        $created = $owner->images()->create($image->toArray());

        $illegal = factory(User::class)->create();

        $this->actingAsUser($illegal)
            ->json('DELETE', 'api/images/' . $created->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_an_admin_can_edit_an_image()
    {
        $image = factory(Image::class)->make();
        $owner = factory(User::class)->create();
        $created = $owner->images()->create($image->toArray());

        $data = factory(Image::class, 'admin_edit')->make()->toArray();

        $this->actingAsAdmin()
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
        $owner = factory(User::class)->create();
        $created = $owner->images()->create($image->toArray());

        $this->actingAsAdmin()
            ->json('DELETE', 'api/images/' . $created->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_a_user_can_not_edit_reserved_fields()
    {
        $this->uploadFile();
        $image = factory(Image::class)->make(['url' => $this->getFakeFile()->filename]);
        $user = $this->getUser();
        $created = $user->images()->create($image->toArray());

        $created->status = 'BANNED';
        $created->visibility = 'PROFILE';
        $created->url = 'Bar';
        $created->save();

        $this->actingAsUser($user)
            ->json('PUT', 'api/images/' . $created->id, [
                'status' => 'APPROVED',
                'visibility' => 'ALL'
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
                'name' => 'Guest image',
                'url' => 'test.jpg'
            ])
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_guest_can_not_edit_images()
    {
        $image = factory(Image::class)->make();
        $owner = factory(User::class)->create();
        $created = $owner->images()->create($image->toArray());

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
        $owner = factory(User::class)->create();
        $created = $owner->images()->create($image->toArray());

        $this->json('DELETE', 'api/images/' . $created->id)
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_an_image_can_be_publicly_accessed()
    {
        $image = factory(Image::class, 'without_relationships')->create();

        $this->json('GET', 'api/images/' . $image->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => $image->toArray()
            ]);
    }

    public function test_the_image_list_can_be_publicly_accessed()
    {
        factory(Image::class, 'without_relationships', 10)->create();

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
                        'url_disc',
                        'url_cutoff',
                        'gravity',
                        'status',
                        'visibility',
                        'featured',
                        'sales',
                        'visits',
                        'width',
                        'width_disc',
                        'height_disc',
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
        $owner = factory(User::class)->create();
        $created = $owner->images()->create($image->toArray());

        $this->actingAsUser($owner)
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
        $contest = factory(Contest::class)->create();

        $image = factory(Image::class)->make();
        $owner = factory(User::class)->create();
        $created = $owner->images()->create($image->toArray());

        $this->actingAsUser($owner)
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

    public function test_an_image_has_an_image_file()
    {
        $this->uploadFile();
        $file = $this->getFakeFile();
        $image = factory(Image::class)->make(['url' => $this->getFakeFile()->filename]);

        $this->actingAsUser($this->getUser())
            ->json('POST', 'api/images', $image->toArray())
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => $image->name,
                    'width' => $file->width,
                    'height' => $file->height,
                    'orientation' => $file->orientation
                ]
            ]);
    }

    public function test_an_admin_can_approve_an_image()
    {
        Event::fake();

        $image = factory(Image::class, 'without_relationships')->create();

        $response = $this->actingAsAdmin()
            ->json('PUT', 'api/images/' . $image->id, ['status' => 'APPROVED'])
            ->assertStatus(200)
            ->assertJson([
                'data' => ['status' => 'APPROVED'],
                'success' => true
            ]);

        Event::assertDispatched(ImageApproved::class, function ($event) use ($image) {
            return $event->image->id === $image->id;
        });
    }

    public function test_a_guest_can_flag_an_image()
    {
        $image = factory(Image::class, 'without_relationships')->create();
        $this->json('POST', 'api/images/' . $image->id . '/flag', ['message' => 'Foo'])
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseHas('emails', [
            'subject' => 'Imagen ' . $image->id . ' reportada'
        ]);
    }
}
