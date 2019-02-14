<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up () {

        Schema::create('monthlyarticles', function( Blueprint $table )
        {
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
            $table->integer('monthlyreport_id')->unsigned()->nullable();
            $table->foreign('monthlyreport_id')->references('id')->on('monthlyreports');
            $table->integer('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->nullable();
            $table->integer('subcategory_id')->unsigned()->nullable();
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('cascade')->nullable();
            $table->timestamps();
        });
        Schema::create('weeklyarticles', function( Blueprint $table )
        {
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
            $table->integer('weeklyreport_id')->unsigned()->nullable();
            $table->foreign('weeklyreport_id')->references('id')->on('weeklyreports')->onDelete('cascade');
            $table->integer('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->nullable();
            $table->integer('subcategory_id')->unsigned()->nullable();
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('cascade')->nullable();
            $table->timestamps();
        });
        Schema::create('yearlyarticles', function( Blueprint $table )
        {
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
            $table->integer('yearlysubcategory_id')->unsigned()->nullable();
            $table->foreign('yearlysubcategory_id')->references('id')->on('yearlysubcategories')->onDelete('cascade')->nullable();
            $table->integer('yearlycategory_id')->unsigned()->nullable();
            $table->foreign('yearlycategory_id')->references('id')->on('yearlycategories')->onDelete('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down () {
        Schema::dropIfExists('yearlyarticles');
        Schema::dropIfExists('weeklyarticles');
        Schema::dropIfExists('monthlyarticles');
    }
}
