<?php

use Faker\Generator as Faker;

$factory->define(App\Format::class, function (Faker $faker) {
    $sizes = ['small', 'medium', 'large', 'xl'];
    $types =['FRAME', 'DISC', 'CUTOFF'];

    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'size' => $sizes[array_rand($sizes)],
        'type' => $types[array_rand($types)],
        'fixed' => $faker->boolean,
        'enabled' => $faker->boolean,
        'price' => $faker->numberBetween(0, 100),
        'cost' => $faker->numberBetween(0, 100),
        'frame_price' => $faker->numberBetween(0, 100),
        'frame_cost' => $faker->numberBetween(0, 100),
        'glass_price' => $faker->numberBetween(0, 100),
        'glass_cost' => $faker->numberBetween(0, 100),
        'pack_price' => $faker->numberBetween(0, 100),
        'pack_cost' => $faker->numberBetween(0, 100),
        'side' => $faker->numberBetween(0, 100),
        'minimum_pixels' => $faker->numberBetween(100, 2000),
    ];
});
