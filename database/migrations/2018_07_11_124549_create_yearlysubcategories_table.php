<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYearlysubcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yearlysubcategories', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->text('slug')->nullable();
            $table->integer('yearlycategory_id')->unsigned();
            $table->foreign('yearlycategory_id')->references('id')->on('yearlycategories')->onDelete('cascade');
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
        Schema::dropIfExists('yearlysubcategories');
    }
}
