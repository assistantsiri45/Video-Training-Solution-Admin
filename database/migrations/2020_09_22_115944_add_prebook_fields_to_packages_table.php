<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrebookFieldsToPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->tinyInteger('is_prebook')->default(0)
                    ->comment("0=> NOT_PREBOOK, 1=> PREBOOK")
                    ->after('pendrive');
            $table->string('prebook_launch_date')->nullable()->after('is_prebook');
            $table->double('booking_amount')->nullable()->after('prebook_launch_date');
            $table->double('prebook_price')->nullable()->after('booking_amount');
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
            $table->tinyInteger('is_prebook')->default(0)
                ->comment("0=> NOT_PREBOOK, 1=> PREBOOK")
                ->after('pendrive');
            $table->string('prebook_launch_date')->nullable()->after('is_prebook');
            $table->double('booking_amount')->nullable()->after('prebook_launch_date');
            $table->double('prebook_price')->nullable()->after('booking_amount');
        });
    }
}
