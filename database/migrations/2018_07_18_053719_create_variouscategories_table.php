<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariouscategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variouscategories', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->text('slug')->nullable();
            $table->integer('variousreport_id')->unsigned();
            $table->foreign('variousreport_id')->references('id')->on('variousreports')->onDelete('cascade');
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
        Schema::dropIfExists('variouscategories');
    }
}
