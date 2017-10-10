<?php

use Faker\Generator as Faker;

$factory->define(App\Frame::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'border' => md5($faker->word) . '.jpg',
        'border_mobile' => md5($faker->word) . '.jpg',
        'thumbnail' => md5($faker->word) . '.jpg'
    ];
});
