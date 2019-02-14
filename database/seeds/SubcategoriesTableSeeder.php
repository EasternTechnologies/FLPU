<?php

use Illuminate\Database\Seeder;

class SubcategoriesTableSeeder extends Seeder
{
    private static $data_1 = [
      'rak_komp_zemlja'=>'Тактические и оперативно-тактические ракетные комплексы, системы класса "земля-земля", в т.ч. РСЗО и ПТРК',
      'rak_komp_zenit'=>'Зенитные ракетные комплексы и системы',

    ];

    private static $data_2 = [
      'sputnik_svjaz'=>'Спутниковые средства связи',
      'radiosvjaz'=>'Наземные средства связи (в т.ч. радиосвязь)',

    ];
    private static $data_3 = [
      'razvedka'=>'Радиолокационная, радио- и радиотехническая, оптико-электронная разведка',
      'borjba'=>'Радиоэлектронная борьба',

    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = \Illuminate\Support\Facades\DB::table('subcategories');
        //$table->truncate();
        foreach ( static::$data_1 as $key=> $item ) {
            $table->insert([
              'title' => $item,
              'slug' => $key,
              'category_id' => 12
            ]);
        }
        foreach ( static::$data_2 as $key=> $item ) {
            $table->insert([
              'title' => $item,
              'slug' => $key,
              'category_id' => 13
            ]);
        }
        foreach ( static::$data_3 as $key=> $item ) {
            $table->insert([
              'title' => $item,
              'slug' => $key,
              'category_id' => 14
            ]);
        }
    }
}
