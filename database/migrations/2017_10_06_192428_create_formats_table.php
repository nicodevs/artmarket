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
            $table->string('name', 50)->default('');
            $table->string('description')->nullable()->default('');
            $table->string('size', 50)->nullable()->default('');
            $table->string('type', 50)->default('frame');
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
