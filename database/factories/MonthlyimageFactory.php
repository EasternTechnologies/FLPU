<?php

use Faker\Generator as Faker;

$factory->define(App\Monthlyimage::class, function (Faker $faker) {
    return [
      'image' => $faker->imageUrl(640, 480, 'technics'),
      'thumbnail' => $faker->imageUrl(640, 480, 'technics'),
      'monthlyarticle_id' => random_int(1, 50)
    ];
});
