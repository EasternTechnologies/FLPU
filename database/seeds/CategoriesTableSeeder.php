<?php

    use Illuminate\Database\Seeder;

    class CategoriesTableSeeder extends Seeder
    {

        private static $data_1 = [
          'vps'  => 'Военно-политические события',
          'vr'   => 'Военные расходы',
          'hsmr' => 'Характеристика сегментов мирового рынка',
          'srmr' => 'Состояние и развитие вооруженных сил (иностранных государств)',
          'nad'  => 'Некоторые аспекты деятельности национальных ОПК иностранных государств',
          'dei'  => 'Данные по экспорту (импорту) ВВТ',
          'vts'  => 'Военно-техническое сотрудничество',
          'rm'   => 'Разработка и модернизация ВВТ',
          'mv'   => 'Международные выставки ВВТ',
          'oms'  => 'Ограничительные меры и санкции',
          'kn'   => 'Кадровые назначения',
        ];

        private static $data_2 = [
          'rks'  => 'Ракетные комплексы и системы',
          'ss'   => 'Средства связи',
          'sksr' => 'Средства, комплексы и системы разведки и РЭБ',
          'bak' => 'Беспилотные авиационные комплексы',
          'nss'  => 'Навигационные средства и системы',
          'r'  => 'Разное',
        ];
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run () {
            $categories = \Illuminate\Support\Facades\DB::table('categories');
            //$categories->truncate();
            foreach ( static::$data_1 as $key => $item ) {
                $categories->insert([
                  'title' => $item,
                  'slug' => $key,
                    'report_type_id' => 1
                ]);
            }
            foreach ( static::$data_2 as $key =>$item ) {
                $categories->insert([
                  'title' => $item,
                  'slug' => $key,
                  'report_type_id' => 2
                ]);
            }
        }
    }
