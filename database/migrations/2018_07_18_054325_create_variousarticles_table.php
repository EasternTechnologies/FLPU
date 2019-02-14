<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariousarticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variousarticles', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title')->nullable();
            $table->longText('body')->nullable();
            $table->text('year');
            $table->text('month');
            $table->text('week');
            $table->text('start_period')->nullable();
            $table->text('end_period')->nullable();
            $table->boolean('published')->default(FALSE);
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('varioussubcategory_id')->unsigned()->nullable();
            $table->foreign('varioussubcategory_id')->references('id')->on('varioussubcategories')->onDelete('cascade')->nullable();
            $table->integer('variouscategory_id')->unsigned()->nullable();
            $table->foreign('variouscategory_id')->references('id')->on('variouscategories')->onDelete('cascade')->nullable();
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
        Schema::dropIfExists('variousarticles');
    }
}
