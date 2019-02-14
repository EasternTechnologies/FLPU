<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountrycatalogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countrycatalogs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('number');
            $table->text('year');
            $table->text('month');
            $table->text('start_date')->nullable();
            $table->text('end_date')->nullable();
            $table->text('week');
            $table->integer('published')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countrycatalogs');
    }
}
