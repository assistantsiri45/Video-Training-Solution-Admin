<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NullableFieldsInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->float('cgst')->nullable()->change();
            $table->float('cgst_amount')->nullable()->change();
            $table->longText('unique_key')->nullable()->change();
            $table->integer('payment_mode')->default(1)->comment('1=>ONLINE, 2=>CASH ON DELIVERY, 3=>PREPAID')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->float('cgst')->change();
            $table->float('cgst_amount')->change();
            $table->longText('unique_key')->change();
            $table->integer('payment_mode')->default(1)->comment('1=>ONLINE, 2=>CASH ON DELIVERY')->change();
        });
    }
}
