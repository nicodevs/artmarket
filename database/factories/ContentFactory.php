<?php

use Faker\Generator as Faker;

$factory->define(App\Content::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'content' => $faker->paragraph,
        'in_sidebar' => $faker->numberBetween(0, 1),
        'sequence' => $faker->numberBetween(0, 10)
    ];
});
