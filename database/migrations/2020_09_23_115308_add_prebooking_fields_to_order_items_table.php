<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrebookingFieldsToOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->tinyInteger('is_prebook')->default(0)
                ->comment("0=> NOT_PREBOOK, 1=> PREBOOK")
                ->after('price_type');
            $table->double('booking_amount')->nullable()->after('is_prebook');
            $table->tinyInteger('is_booking_amount_paid')->default(0)
                ->comment("0=> NOT PAID, 1=> PAID")
                ->after('booking_amount');
            $table->double('balance_amount')->nullable()->after('is_booking_amount_paid');
            $table->tinyInteger('is_balance_amount_paid')->default(0)
                ->comment("0=> NOT PAID, 1=> PAID")
                ->after('balance_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->tinyInteger('is_prebook')->default(0)
                ->comment("0=> NOT_PREBOOK, 1=> PREBOOK")
                ->after('price_type');
            $table->double('booking_amount')->nullable()->after('is_prebook');
            $table->tinyInteger('is_booking_amount_paid')->default(0)
                ->comment("0=> NOT PAID, 1=> PAID")
                ->after('booking_amount');
            $table->double('balance_amount')->nullable()->after('is_booking_amount_paid');
            $table->tinyInteger('is_balance_amount_paid')->default(0)
                ->comment("0=> NOT PAID, 1=> PAID")
                ->after('balance_amount');
        });
    }
}
