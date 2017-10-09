<?php

use Faker\Generator as Faker;

$factory->define(App\Contest::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'terms' => $faker->paragraph,
        'cover' => md5($faker->word) . '.jpg',
        'prize_image_desktop' => md5($faker->word) . '.jpg',
        'prize_image_mobile' => md5($faker->word) . '.jpg',
        'winners_image_desktop' => md5($faker->word) . '.jpg',
        'winners_image_mobile' => md5($faker->word) . '.jpg',
        'expires_at' => $faker->date('Y-m-d H:i:s', '+10 years')
    ];
});
