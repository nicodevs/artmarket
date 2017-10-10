<?php

use Faker\Generator as Faker;

$factory->define(App\Coupon::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph,
        'discount' => $faker->numberBetween(1, 100),
        'max_uses' => $faker->numberBetween(0, 100),
        'expires_at' => $faker->date('Y-m-d H:i:s', '+10 years')
    ];
});
