<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContestsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 20);
            $table->string('title');
            $table->string('description')->nullable();
            $table->text('terms', 16777215);
            $table->string('cover_desktop', 150)->nullable();
            $table->string('cover_mobile', 150)->nullable();
            $table->string('prize_image_desktop', 150)->nullable();
            $table->string('prize_image_mobile', 150)->nullable();
            $table->string('winners_image_desktop', 150)->nullable();
            $table->string('winners_image_mobile', 150)->nullable();
            $table->dateTime('expires_at')->nullable();
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
        Schema::drop('contests');
    }
}
