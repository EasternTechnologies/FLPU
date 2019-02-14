<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateCategoriesTable extends Migration
    {

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up () {
            Schema::create('categories', function( Blueprint $table )
            {
                $table->increments('id');
                $table->text('title');
                $table->text('slug');
                $table->integer('report_type_id')->unsigned();
                $table->foreign('report_type_id')->references('id')->on('report_types')->onDelete('cascade');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down () {
            Schema::dropIfExists('categories');
        }
    }
