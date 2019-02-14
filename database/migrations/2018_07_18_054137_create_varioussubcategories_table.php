<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVarioussubcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('varioussubcategories', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->text('slug')->nullable();
            $table->integer('variouscategory_id')->unsigned();
            $table->foreign('variouscategory_id')->references('id')->on('variouscategories')->onDelete('cascade');
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
        Schema::dropIfExists('varioussubcategories');
    }
}
