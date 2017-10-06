<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->char('public_id', 8)->default('');
            $table->integer('user_id')->unsigned();
            $table->integer('products_quantity')->unsigned()->nullable();
            $table->integer('full_price')->unsigned()->default(0);
            $table->integer('discount')->unsigned()->default(0);
            $table->integer('price')->unsigned()->nullable();
            $table->integer('cost')->unsigned()->nullable();
            $table->integer('commision_author')->unsigned()->nullable();
            $table->integer('commision_gateway')->unsigned()->nullable();
            $table->string('coupon_code', 50)->nullable();
            $table->string('coupon_discount', 50)->nullable();
            $table->integer('coupon_id')->unsigned()->nullable();
            $table->boolean('paid')->default(0);
            $table->integer('payment_id')->unsigned()->default(0);
            $table->integer('status_id')->unsigned()->default(1);
            $table->integer('shipping_id')->unsigned();
            $table->integer('shipping_price')->unsigned();
            $table->string('shipping_address')->default('');
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
        Schema::drop('sales');
    }
}
