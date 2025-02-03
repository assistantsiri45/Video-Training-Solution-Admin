<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugToSpinWheelCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spin_wheel_campaigns', function (Blueprint $table) {
            $table->longText('slug')->nullable()->after('point_validity');
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
            $table->dropColumn('point_validity');
        });
    }
}
