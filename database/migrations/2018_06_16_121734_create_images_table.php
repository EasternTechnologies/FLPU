<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weeklyimages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image');
            $table->string('thumbnail');
            $table->integer('weeklyarticle_id')->references('id')->on('weeklyarticles')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('monthlyimages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image');
            $table->string('thumbnail');
            $table->integer('monthlyarticle_id')->references('id')->on('monthlyarticles')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('infocountryimages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image');
            $table->string('thumbnail');
            $table->integer('info_country_id')->references('id')->on('info_countries')->onDelete('cascade');
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
        Schema::dropIfExists('weeklyimages');
        Schema::dropIfExists('monthlyimages');
        Schema::dropIfExists('infocountryimages');
    }
}
