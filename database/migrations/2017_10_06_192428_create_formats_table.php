<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFormatsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('description');
            $table->string('size', 10);
            $table->string('type', 10);
            $table->boolean('fixed')->default(0);
            $table->boolean('enabled')->default(0);
            $table->integer('price')->unsigned();
            $table->integer('cost')->unsigned();
            $table->integer('frame_price')->unsigned();
            $table->integer('frame_cost')->unsigned();
            $table->integer('glass_price')->unsigned();
            $table->integer('glass_cost')->unsigned();
            $table->integer('pack_price')->unsigned();
            $table->integer('pack_cost')->unsigned();
            $table->integer('side')->unsigned();
            $table->integer('minimum_pixels')->unsigned();
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
        Schema::drop('formats');
    }
}
