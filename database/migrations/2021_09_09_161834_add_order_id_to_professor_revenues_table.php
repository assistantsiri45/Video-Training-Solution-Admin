<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderIdToProfessorRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('professor_revenues', function (Blueprint $table) {
            $table->integer('order_id')->after('invoice_id')->nullable();
            $table->integer('order_item_id')->after('order_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('professor_revenues', function (Blueprint $table) {
            $table->dropColumn('order_id');
            $table->dropColumn('order_item_id');
        });
    }
}
