<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename');
            $table->string('extension');
            $table->string('mime_type');
            $table->string('size');
            $table->string('width');
            $table->string('height');
            $table->string('orientation');
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
        Schema::dropIfExists('image_files');
    }
}
