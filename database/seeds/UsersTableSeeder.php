<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run () {
        User::create([
          'name'     => 'shultz',
          'email'    => 'skorohods@mail.ru',
          'password' => bcrypt('accord'),
        ])->roles()->attach(1);

        User::create([
          'name'     => 'pavelzv5',
          'email'    => 'pavel.zholnerovich@yandex.ru',
          'password' => bcrypt('mGpZVPs4'),
        ])->roles()->attach(1);
    }
}
