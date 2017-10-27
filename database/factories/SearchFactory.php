<?php

use Faker\Generator as Faker;

$factory->define(App\Search::class, function (Faker $faker) {
    $user = factory(App\User::class)->create();
    return [
        'user_id' => $user->id,
        'keyword' => $faker->word,
        'results_count' => $faker->randomDigitNotNull
    ];
});
