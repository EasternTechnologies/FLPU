<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYearlycategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yearlycategories', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->text('slug')->nullable();
            $table->integer('yearlyreport_id')->unsigned();
            $table->foreign('yearlyreport_id')->references('id')->on('yearlyreports')->onDelete('cascade');
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
        Schema::dropIfExists('yearlycategories');
    }
}
