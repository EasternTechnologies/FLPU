<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlannedexhibitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plannedexhibitions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->text('region')->nullable();
            $table->text('country')->nullable();
            $table->text('place')->nullable();
            $table->text('start');
            $table->text('fin');
            $table->text('theme')->nullable();
            $table->text('description');
            $table->integer('published')->default(0);
            $table->integer('plannedexhibitionyear_id')->unsigned();
            $table->foreign('plannedexhibitionyear_id')->references('id')->on('plannedexhibitionyears')->onDelete('cascade');

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
        Schema::dropIfExists('plannedexhibitions');
    }
}
