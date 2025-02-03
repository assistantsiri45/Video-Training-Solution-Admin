<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSegmentsInSpinWheelSegmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spin_wheel_segments', function (Blueprint $table) {
            $table->integer('value')->after('title')->nullable();
            $table->integer('value_type')->default(1)->comment('1=>Fixed, 2=>percentage,3=>buy_one_get_one')->after('value');
            $table->integer('success_percentage')->after('value_type');
            $table->integer('hits_in_hundred')->after('success_percentage');
            $table->dropColumn('point');
            $table->dropColumn('hit_number');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spin_wheel_segments', function (Blueprint $table) {
            //
        });
    }
}
