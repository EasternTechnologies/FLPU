<?php

    use Illuminate\Database\Seeder;

    class RolesTableSeeder extends Seeder
    {

        private static $data = [
          'admin'=> 'Администратор',
          'manager'=> 'Менеджер',
          'employee'=> 'Сотрудник',
          'user'=> 'Пользователь',
          'analyst'=> 'Аналитик'
        ];

        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run () {
            $table = \Illuminate\Support\Facades\DB::table('roles');
            foreach ( static::$data as $title =>$name ) {
                $table->insert([
                  'name' => $name,
                  'title' => $title,
                ]);
            }
        }
    }
