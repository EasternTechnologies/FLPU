<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateSubcategoriesTable extends Migration
    {



        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up () {
            Schema::create('subcategories', function( Blueprint $table )
            {
                $table->increments('id');
                $table->text('title');
                $table->text('slug');
                $table->integer('category_id')->unsigned();
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down () {
            Schema::dropIfExists('subcategories');
        }
    }
