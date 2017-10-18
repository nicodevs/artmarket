<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSizesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->default('');
            $table->integer('max_frame')->unsigned();
            $table->integer('max_poster')->unsigned();
            $table->integer('max_disc')->unsigned();
            $table->integer('max_cutoff')->unsigned();
            $table->integer('minimum_pixels')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sizes');
    }
}
