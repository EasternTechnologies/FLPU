<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->longText('overview');
            $table->text('start_date')->nullable();
            $table->text('end_date')->nullable();
            $table->integer('countrycatalog_id')->unsigned()->nullable();
            $table->foreign('countrycatalog_id')->references('id')->on('countrycatalogs');
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
        Schema::dropIfExists('regions');
    }
}
