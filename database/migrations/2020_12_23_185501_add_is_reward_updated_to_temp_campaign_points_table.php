<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsRewardUpdatedToTempCampaignPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_campaign_points', function (Blueprint $table) {
            $table->boolean('is_reward_updated')->default(0)->after('expire_at');
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
            $table->dropColumn('is_reward_updated');
        });
    }
}
