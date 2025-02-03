<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('package_id');
            $table->integer('price');
            $table->integer('price_type')->default(1)->comment('1=>PRICE, 2=>DISCOUNTED PRICE, 3=>SPECIAL PRICE, 4=>PEN DRIVE PRICE, 5=>PEN DRIVE DISCOUNTED PRICE, 6=>PEN DRIVE SPECIAL PRICE');
            $table->integer('delivery_mode')->default(1)->comment('1=>ONLINE, 2=>PEN DRIVE');
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
        Schema::dropIfExists('order_items');
    }
}
