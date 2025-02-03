<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPendriveToPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->tinyInteger('pendrive')->after('price')
                  ->default(0)
                  ->comment("0=>FALSE,1=> TRUE");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->tinyInteger('pendrive')->after('price')
                ->default(0)
                ->comment("0=> ONLINE PAYMENT,1=> PENDRIVE");
        });
    }
}
