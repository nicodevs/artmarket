<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCombosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('slug', 200);
            $table->string('description', 200)->nullable();
            $table->string('thumbnail', 200)->nullable()->default('');
            $table->string('thumbnail_mobile', 200)->nullable()->default('');
            $table->text('cart', 16777215)->nullable();
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
        Schema::drop('combos');
    }
}
