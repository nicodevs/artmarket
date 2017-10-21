<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Notification::class, function (Faker $faker) {
    $types =['LIKE', 'APPROVAL', 'COMMENT'];

    return [
        'user_id' => 1,
        'image_id' => 1,
        'type' => $types[array_rand($types)],
        'description' => $faker->sentence
    ];
});
