<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class WeeklyreportTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run ( Faker $faker ) {
            for ( $w = 1; $w <= 24; $w++ ) {
                $report = \App\Weeklyreport::create([
                  'year'       => 2017,
                  'month'      => ceil($w/4),
                  'start_date' => NULL,
                  'end_date'   => null,
                  'week'       => $w,
                ]);
            }
    }
}
