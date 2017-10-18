<?php

use Faker\Generator as Faker;

$factory->define(App\Image::class, function (Faker $faker) {
    $categories = factory(App\Category::class, 3)->create()->pluck('id');

    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'url' => md5($faker->word) . '.jpg',
        'tags' => $faker->word,
        'categories' => $categories
    ];
});

$factory->defineAs(App\Image::class, 'without_relationships', function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'url' => md5($faker->word) . '.jpg',
        'tags' => $faker->word
    ];
});

$factory->defineAs(App\Image::class, 'user_edit', function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'tags' => $faker->word
    ];
});

$factory->defineAs(App\Image::class, 'admin_edit', function (Faker $faker) {
    $visibility = ['ALL', 'PROFILE'];
    $status = ['BANNED', 'APPROVED'];

    return [
        'name' => $faker->word,
        'visibility' => $visibility[array_rand($visibility)],
        'featured' => $faker->boolean,
        'status' => $status[array_rand($status)],
        'extra' => json_encode([
            'cutoff' => [],
            'disc' => [
                md5($faker->word) . '.jpg'
            ]
        ])
    ];
});
