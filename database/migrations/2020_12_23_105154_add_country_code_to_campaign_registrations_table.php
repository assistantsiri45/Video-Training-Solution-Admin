<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountryCodeToCampaignRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_registrations', function (Blueprint $table) {
            $table->integer('user_id')->nullable()->after('campaign_id');
            $table->string('country_code')->nullable()->after('user_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_registrations', function (Blueprint $table) {
            $table->dropColumn('country_code');
        });
    }
}
