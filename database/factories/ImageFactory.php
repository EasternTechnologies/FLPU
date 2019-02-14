<?php

use Faker\Generator as Faker;

$factory->define(App\Image::class, function (Faker $faker) {
    return [
        'image' => $faker->imageUrl(640, 480, 'technics'),
        'thumbnail' => $faker->imageUrl(640, 480, 'technics'),
        'article_id' => random_int(1, 1600)
    ];
});
