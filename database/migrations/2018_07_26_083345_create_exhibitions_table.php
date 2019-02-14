<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExhibitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exhibitions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->text('startdate');
            $table->text('enddate');
            $table->text('region');
            $table->text('place');
            $table->text('country');
            $table->text('theme');
            $table->integer('exhibitionyear_id')->unsigned();
            $table->foreign('exhibitionyear_id')->references('id')->on('exhibitionyears')->onDelete('cascade');
            $table->longText('description');
            $table->longText('event_general_information');
            $table->longText('participants');
            $table->longText('results');
            $table->longText('military_situation');
            $table->longText('country_general_information');
            $table->longText('military_expenses');
			$table->longText('vvt');
            $table->longText('general_tender');
            $table->longText('features_of_stay');
            $table->boolean('published')->default(0);
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
        Schema::dropIfExists('exhibitions');
    }
}
