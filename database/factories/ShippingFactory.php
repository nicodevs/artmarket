<?php

use Faker\Generator as Faker;

$factory->define(App\Shipping::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'price' => $faker->numberBetween(1, 100),
        'pickup' => $faker->boolean,
        'enabled' => $faker->boolean
    ];
});
