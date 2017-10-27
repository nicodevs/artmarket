<?php

use Faker\Generator as Faker;

$factory->define(App\Combo::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'thumbnail' => md5($faker->word) . '.jpg',
        'thumbnail_mobile' => md5($faker->word) . '.jpg',
        'cart' => $faker->word
    ];
});
