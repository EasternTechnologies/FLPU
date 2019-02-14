<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfocountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_countries', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->longText('general_data')->nullable();
            $table->longText('expenses')->nullable();
            $table->longText('situation')->nullable();
            $table->longText('military_structure')->nullable();
            $table->longText('major_types_vvt')->nullable();
            $table->longText('military_industry')->nullable();
            $table->longText('military_technical_cooperation')->nullable();
            $table->longText('overview');
            $table->text('start_date')->nullable();
            $table->text('end_date')->nullable();
            $table->integer('region_id')->unsigned()->nullable();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
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
        Schema::dropIfExists('info_countries');
    }
}
