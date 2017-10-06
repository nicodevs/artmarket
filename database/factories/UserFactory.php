<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    $firstName = $faker->firstName;
    $lastName = $faker->lastName;

    return [
        'name' => $firstName . ' ' . $lastName,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret')
    ];
});

$factory->defineAs(App\User::class, 'admin', function ($faker) use ($factory) {
    $user = $factory->raw('App\User');
    return array_merge($user, ['admin' => 1]);
});
