<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->double('cgst')->after('commission');
            $table->double('cgst_amount')->after('cgst');
            $table->double('igst')->after('cgst_amount');
            $table->double('igst_amount')->after('igst');
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
            $table->double('cgst')->after('commission');
            $table->double('cgst_amount')->after('cgst');
            $table->double('igst')->after('cgst_amount');
            $table->double('igst_amount')->after('igst');
        });
    }
}
