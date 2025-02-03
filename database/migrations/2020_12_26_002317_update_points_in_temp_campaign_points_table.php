<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePointsInTempCampaignPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_campaign_points', function (Blueprint $table) {
            $table->integer('value')->after('campaign_id')->nullable();
            $table->integer('value_type')->after('value')->nullable();
            $table->integer('user_id')->after('campaign_registration_id')->nullable();
            $table->integer('campaign_registration_id')->nullable()->change();
            $table->integer('is_used')->default(0)->after('value_type');
            $table->dropColumn('is_reward_updated');
            $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_campaign_points', function (Blueprint $table) {
            //
        });
    }
}
