<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'cover' => md5($faker->word) . '.jpg',
        'thumbnail' => md5($faker->word) . '.jpg'
    ];
});
