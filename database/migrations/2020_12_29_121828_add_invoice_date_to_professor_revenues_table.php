<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceDateToProfessorRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('professor_revenues', function (Blueprint $table) {
            $table->dateTime('invoice_date')->nullable()->after('revenue_amount');
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
            $table->dropColumn('invoice_date');
        });
    }
}
