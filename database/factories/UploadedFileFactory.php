<?php

use Faker\Generator as Faker;

$factory->define(App\UploadedFile::class, function (Faker $faker) {
    return [
        'extension' => 'jpg',
        'mime_type' => 'image/jpeg',
        'size' => 1536000,
        'width' => 2000,
        'height' => 3000,
        'orientation' => 'portrait'
    ];
});
