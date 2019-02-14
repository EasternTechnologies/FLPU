<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVvtTypeWeeklyarticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vvt_type_weeklyarticle', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vvt_type_id')->index();
            $table->integer('weeklyarticle_id')->index();
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
        Schema::dropIfExists('vvt_type_weeklyarticle');
    }
}
