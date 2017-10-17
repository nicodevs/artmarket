<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageFileTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_not_upload_files_of_invalid_type()
    {
        $response = $this->actingAsUser(factory(User::class)->create())
            ->json('POST', 'api/images/files', [
                'image' => 'test'
            ])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'invalid_fields'
            ]);

        $this->assertEquals(3, count(json_decode($response->getContent())->errors->image));
    }

    public function test_the_image_file_should_have_a_minimum_width()
    {
        $response = $this->actingAsUser(factory(User::class)->create())
            ->json('POST', 'api/images/files', [
                'image' => UploadedFile::fake()->image('photo.jpg', 100, 1500)->size(1500)
            ])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'invalid_fields'
            ])
            ->assertJsonStructure([
                'errors' => ['image']
            ]);

        $this->assertEquals(1, count(json_decode($response->getContent())->errors->image));
    }

    public function test_the_image_file_should_have_a_minimum_height()
    {
        $response = $this->actingAsUser(factory(User::class)->create())
            ->json('POST', 'api/images/files', [
                'image' => UploadedFile::fake()->image('photo.jpg', 1500, 100)->size(1500)
            ])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'invalid_fields'
            ])
            ->assertJsonStructure([
                'errors' => ['image']
            ]);

        $this->assertEquals(1, count(json_decode($response->getContent())->errors->image));
    }

    public function test_the_image_file_should_have_a_minimum_size()
    {
        $response = $this->actingAsUser(factory(User::class)->create())
            ->json('POST', 'api/images/files', [
                'image' => UploadedFile::fake()->image('photo.jpg', 1500, 1500)->size(100)
            ])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'invalid_fields'
            ])
            ->assertJsonStructure([
                'errors' => ['image']
            ]);

        $this->assertEquals(1, count(json_decode($response->getContent())->errors->image));
    }

    public function test_a_user_can_upload_an_image_file()
    {
        Storage::fake('uploads');

        $response = $this->actingAsUser(factory(User::class)->create())
            ->json('POST', 'api/images/files', [
                'image' => UploadedFile::fake()->image('photo.jpg', 2000, 3000)->size(1500)
            ])
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'extension' => 'jpg',
                    'mime_type' => 'image/jpeg',
                    'size' => 1536000,
                    'width' => 2000,
                    'height' => 3000,
                    'orientation' => 'portrait'
                ]
            ]);

        $filename = json_decode($response->getContent())->data->filename;

        Storage::disk('uploads')->assertExists('temporal/' . $filename);
    }
}
