<?php

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run ( Faker $faker ) {
        $table = \Illuminate\Support\Facades\DB::table('articles');

        for ( $m = 1; $m <= 6; $m++ ) {
            for ( $w = 1; $w <= 24; $w++ ) {
                for ( $i = 1; $i <= 11; $i++ ) {

                    $article = \App\Article::create([
                      'body'           => $faker->paragraphs(20, true),
                      'title'          => $faker->country,
                      //'user_id'        => 1,
                      'report_type_id' => 1,
                      'category_id'    => $i,
                      'subcategory_id' => NULL,
                      'year'           => 2015,
                      'month'          => $m,
                      'week'           => $w,
                    ]);
                    $article->companies()->attach(range(1,5));
                    $article->countries()->attach(range(1,5));
                    $article->vvttypes()->attach(range(1,5));
                    $article->personalities()->attach(range(1,5));
                }
            }
        }
        for ( $m = 1; $m <= 12; $m++ ) {
            for ( $i = 12; $i <= 17; $i++ ) {
                if ( $i == 12 ) {
                    $article = \App\Article::create([
                      'body'           => $faker->paragraphs(20, true),
                      'title'          => $faker->country,
                      'user_id'        => 1,
                      'report_type_id' => 2,
                      'category_id'    => $i,
                      'subcategory_id' => 1,
                      'year'           => 2015,
                      'month'          => $m,
                      'week'           => 4,

                    ]);
                    $article->companies()->attach(range(1,5));
                    $article->countries()->attach(range(1,5));
                    $article->vvttypes()->attach(range(1,5));
                    $article->personalities()->attach(range(1,5));
                    $article = \App\Article::create([
                      'body'           => $faker->paragraphs(20, true),
                      'title'          => $faker->country,
                      'user_id'        => 1,
                      'report_type_id' => 2,
                      'category_id'    => $i,
                      'subcategory_id' => 2,
                      'year'           => 2015,
                      'month'          => $m,
                      'week'           => 4,

                    ]);
                    $article->companies()->attach(range(1,5));
                    $article->countries()->attach(range(1,5));
                    $article->vvttypes()->attach(range(1,5));
                    $article->personalities()->attach(range(1,5));
                }elseif($i == 13){
                    $article = \App\Article::create([
                      'body'           => $faker->paragraphs(20, true),
                      'title'          => $faker->country,
                      'user_id'        => 1,
                      'report_type_id' => 2,
                      'category_id'    => $i,
                      'subcategory_id' => 3,
                      'year'           => 2015,
                      'month'          => $m,
                      'week'           => 4,

                    ]);
                    $article->companies()->attach(range(1,5));
                    $article->countries()->attach(range(1,5));
                    $article->vvttypes()->attach(range(1,5));
                    $article->personalities()->attach(range(1,5));
                    $article = \App\Article::create([
                      'body'           => $faker->paragraphs(20, true),
                      'title'          => $faker->country,
                      'user_id'        => 1,
                      'report_type_id' => 2,
                      'category_id'    => $i,
                      'subcategory_id' => 4,
                      'year'           => 2015,
                      'month'          => $m,
                      'week'           => 4,

                    ]);
                    $article->companies()->attach(range(1,5));
                    $article->countries()->attach(range(1,5));
                    $article->vvttypes()->attach(range(1,5));
                    $article->personalities()->attach(range(1,5));
            }elseif($i == 14){
                    $article = \App\Article::create([
                      'body'           => $faker->paragraphs(20, true),
                      'title'          => $faker->country,
                      'user_id'        => 1,
                      'report_type_id' => 2,
                      'category_id'    => $i,
                      'subcategory_id' => 5,
                      'year'           => 2015,
                      'month'          => $m,
                      'week'           => 4,

                    ]);
                    $article->companies()->attach(range(1,5));
                    $article->countries()->attach(range(1,5));
                    $article->vvttypes()->attach(range(1,5));
                    $article->personalities()->attach(range(1,5));
                    $article = \App\Article::create([
                      'body'           => $faker->paragraphs(20, true),
                      'title'          => $faker->country,
                      'user_id'        => 1,
                      'report_type_id' => 2,
                      'category_id'    => $i,
                      'subcategory_id' => 6,
                      'year'           => 2015,
                      'month'          => $m,
                      'week'           => 4,

                    ]);
                    $article->companies()->attach(range(1,5));
                    $article->countries()->attach(range(1,5));
                    $article->vvttypes()->attach(range(1,5));
                    $article->personalities()->attach(range(1,5));
                }else{
                    $article = \App\Article::create([
                      'body'           => $faker->paragraphs(20, true),
                      'title'          => $faker->country,
                      'user_id'        => 1,
                      'report_type_id' => 2,
                      'category_id'    => $i,
                      'subcategory_id' => null,
                      'year'           => 2015,
                      'month'          => $m,
                      'week'           => 4,

                    ]);
                    $article->companies()->attach(range(1,5));
                    $article->countries()->attach(range(1,5));
                    $article->vvttypes()->attach(range(1,5));
                    $article->personalities()->attach(range(1,5));
                }


            }
        }

    }
}
