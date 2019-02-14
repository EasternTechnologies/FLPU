<?php

use Faker\Generator as Faker;

$factory->define(App\Personality::class, function (Faker $faker) {
    return [
       'title' => $faker->lastName
    ];
});
