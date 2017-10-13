<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateImagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->default('');
            $table->string('description')->nullable()->default('');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('contest_id')->nullable()->default(0);
            $table->string('tags')->nullable();
            $table->string('orientation')->nullable();
            $table->string('url')->default('');
            $table->string('url_disk')->default('');
            $table->string('url_cutoff')->default('');
            $table->string('gravity')->nullable();
            $table->string('status')->default('REVISION');
            $table->string('visibility')->default('ALL');
            $table->integer('featured')->default(0);
            $table->integer('sales')->unsigned()->default(0);
            $table->integer('visits')->unsigned()->default(0);
            $table->integer('width')->unsigned()->default(0);
            $table->integer('width_disk')->unsigned()->default(0);
            $table->integer('height_disk')->unsigned()->default(0);
            $table->integer('width_cutoff')->unsigned()->default(0);
            $table->integer('height_cutoff')->unsigned()->default(0);
            $table->integer('height')->unsigned()->default(0);
            $table->integer('random')->default(0);
            $table->text('extra', 16777215)->nullable();
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
        Schema::drop('images');
    }
}
