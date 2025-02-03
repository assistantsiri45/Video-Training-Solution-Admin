<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessorRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professor_revenues', function (Blueprint $table) {
            $table->id();
            $table->integer('professor_id')->nullable();
            $table->integer('package_id')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->double('package_total')->nullable();
            $table->double('package_revenue_percentage')->nullable();
            $table->double('professor_contribution_percentage')->nullable();
            $table->double('revenue_amount')->nullable();
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
        Schema::dropIfExists('professor_revenues');
    }
}
