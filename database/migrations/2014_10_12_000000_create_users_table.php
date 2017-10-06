<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 20)->unique('username');
            $table->string('email', 150)->unique('email');
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('ip')->default('');
            $table->string('facebook_id')->nullable();
            $table->boolean('geo_ip')->default(0);
            $table->string('city')->nullable()->default('');
            $table->string('region')->nullable()->default('');
            $table->string('country')->nullable()->default('');
            $table->integer('approved_images_count')->default(0);
            $table->integer('commisions_count')->unsigned()->default(0);
            $table->integer('purchases_count')->unsigned()->default(0);
            $table->integer('commision_rate')->unsigned()->default(50);
            $table->boolean('disabled')->default(0);
            $table->boolean('featured')->default(0);
            $table->boolean('verified')->default(0);
            $table->boolean('admin')->default(0);
            $table->integer('contest_moderator')->nullable();
            $table->string('bio')->nullable()->default('');
            $table->string('link')->nullable();
            $table->string('avatar', 150)->nullable();
            $table->string('cover', 150)->nullable();
            $table->string('featured_thumbnail', 200)->nullable()->default('');
            $table->text('cart', 16777215)->nullable();
            $table->string('password', 200)->default('');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
