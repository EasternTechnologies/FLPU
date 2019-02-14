<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;


class WeeklyarticlesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run ( Faker $faker ) {
        $table = \Illuminate\Support\Facades\DB::table('weeklyarticles');

        for ( $w = 1; $w <= 24; $w++ ) {
            for ( $i = 1; $i <= 11; $i++ ) {
            //for ( $m = 1; $m <= 6; $m++ ) {

                $article = \App\Weeklyarticle::create([
                  'body'            => $faker->paragraphs(20, TRUE),
                  'title'           => $faker->country,
                  //'user_id'        => 1,
                  'weeklyreport_id' => $w,
                  'category_id'     => $i,
                  'subcategory_id'  => NULL,
                  'year'            => 2017,
                  'month'           => ceil($w/4),
                  'week'            => $w,
                ]);

                $article->weeklyreport()->associate($w);
                $article->companies()->attach(range(1, 5));
                $article->countries()->attach(range(1, 5));
                $article->vvttypes()->attach(range(1, 5));
                $article->personalities()->attach(range(1, 5));
            //}
            }
        }
    }

}
