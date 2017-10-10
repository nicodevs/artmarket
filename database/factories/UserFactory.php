<?php

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    $firstName = $faker->firstName;
    $lastName = $faker->lastName;

    return [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret')
    ];
});

$factory->defineAs(App\User::class, 'admin', function (Faker $faker) use ($factory) {
    $user = $factory->raw('App\User');
    return array_merge($user, ['username' => str_random(20), 'admin' => 1]);
});

$factory->defineAs(App\User::class, 'user', function (Faker $faker) use ($factory) {
    $post = $factory->raw('App\User');
    return array_merge($post, ['username' => $faker->word]);
});
