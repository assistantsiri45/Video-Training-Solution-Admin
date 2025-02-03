<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->longText('transaction_id');
            $table->longText('transaction_response');
            $table->longText('unique_key');
            $table->integer('payment_status')->comment('1=>SUCCESS, 2=>FAILED, 3=>RETURN');
            $table->integer('payment_mode')->default(1)->comment('1=>ONLINE, 2=>CASH ON DELIVERY');

            $table->string('coupon_id')->nullable();
            $table->string('coupon_code')->nullable();
            $table->double('coupon_amount')->nullable();
            $table->double('reward_amount')->nullable();
            $table->double('net_amount');

            $table->integer('address_id')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pin')->nullable();
            $table->longText('address')->nullable();

            $table->integer('status')->default(1)->comment('1=> ORDER RECEIVED, 2=> ORDER PROCESSED, 3=>SHIPPED, 4=>RECEIVED');
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
        Schema::dropIfExists('orders');
    }
}
