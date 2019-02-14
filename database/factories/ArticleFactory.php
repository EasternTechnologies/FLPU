<?php

use Faker\Generator as Faker;

$factory->define(App\Article::class, function (Faker $faker) {
    return [
        'body' => $faker->text(1000),
        'user_id' => random_int(1, 10),
        'report_type_id' => random_int(2, 3),
        'category_id' => random_int(1, 3),
        'subcategory_id' => random_int(1, 3),
        'year' => $faker->numberBetween(2015,2017),
        'month' => $faker->month(),
        'week' => $faker->numberBetween(1, 52),
    ];
});
