<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommisionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commisions', function (Blueprint $table) {
            $table->increments('id');
            $table->char('public_id', 8)->default('');
            $table->integer('sale_id')->unsigned();
            $table->char('sale_public_id', 8)->default('');
            $table->integer('image_id')->unsigned();
            $table->integer('format_id')->unsigned();
            $table->integer('frame_id')->unsigned();
            $table->integer('size_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('price')->unsigned()->default(0);
            $table->integer('price_impression')->unsigned()->default(0);
            $table->integer('price_glass')->unsigned()->default(0);
            $table->integer('price_frame')->unsigned()->default(0);
            $table->integer('price_pack')->unsigned()->default(0);
            $table->integer('cost')->unsigned()->default(0);
            $table->integer('cost_impression')->unsigned()->default(0);
            $table->integer('cost_glass')->unsigned()->default(0);
            $table->integer('cost_frame')->unsigned()->default(0);
            $table->integer('cost_pack')->unsigned()->default(0);
            $table->integer('commision')->unsigned();
            $table->boolean('casheable')->default(0);
            $table->boolean('cashed')->default(0);
            $table->dateTime('cashed_at')->nullable();
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
        Schema::drop('commisions');
    }
}
