<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlannedexhibitionVvtTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plannedexhibition_vvt_type', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vvt_type_id')->index();
            $table->integer('plannedexhibition_id')->index();
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
        Schema::dropIfExists('plannedexhibition_vvt_type');
    }
}
