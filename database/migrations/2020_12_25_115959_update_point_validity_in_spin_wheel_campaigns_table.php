<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePointValidityInSpinWheelCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spin_wheel_campaigns', function (Blueprint $table) {
            $table->string('no_of_chances')->default(1)->change();
            $table->dateTime('point_validity')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spin_wheel_campaigns', function (Blueprint $table) {
            //
        });
    }
}
