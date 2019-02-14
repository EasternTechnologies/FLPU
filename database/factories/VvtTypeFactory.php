<?php

use Faker\Generator as Faker;

$factory->define(App\VvtType::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
    ];
});
