<?php

    use Illuminate\Database\Seeder;

    class ReporttypesTableSeeder extends Seeder
    {


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run () {
        $table = \Illuminate\Support\Facades\DB::table('report_types');
        //$table->truncate();
        foreach ( \App\ReportType::$data as $key =>$item ) {
            $table->insert([
              'title' => $item,
              'slug' => $key,
            ]);
        }
    }
}
