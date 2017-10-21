<?php

use Faker\Generator as Faker;

$factory->define(App\Email::class, function (Faker $faker) {
    return [
        'subject' => $faker->sentence,
        'recipient' => $faker->safeEmail,
        'sent' => 0,
        'tries' => 0,
        'body' => $faker->paragraph
    ];
});
