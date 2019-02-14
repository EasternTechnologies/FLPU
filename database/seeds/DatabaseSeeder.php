<?php

use App\Company;
use App\Country;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run () {

        //factory(Image::class, 2000)->create();
        //factory(\App\Weeklyimage::class, 500)->create();
        //factory(\App\Monthlyimage::class, 500)->create();
        //factory(\App\Personality::class, 10)->create();
        //factory(\App\VvtType::class, 5)->create();
        //factory(InfoCountry::class, 30)->create();
        //factory(Company::class, 30)->create();
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ReporttypesTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(SubcategoriesTableSeeder::class);
        //$this->call(WeeklyreportTableSeeder::class);
        //$this->call(MonthlyreportsTableSeeder::class);
        //$this->call(WeeklyarticlesTableSeeder::class);
        //$this->call(MonthlyarticlesTableSeeder::class);
        //$this->call(ArticlesTableSeeder::class);
        //factory(Article::class, 30)->create();
    }
}
