<?php

use Faker\Generator as Faker;

$factory->define(App\Slide::class, function (Faker $faker) {
    return [
        'description' => $faker->sentence,
        'desktop' => md5($faker->word) . '.jpg',
        'mobile' => md5($faker->word) . '.jpg',
        'sequence' => $faker->numberBetween(0, 10),
        'href' => $faker->url
    ];
});
