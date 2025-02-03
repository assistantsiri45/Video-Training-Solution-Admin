<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('old_notifications', function (Blueprint $table) {
            $table->integer('type')->after('body')->nullable();
            $table->integer('package_id')->after('type')->nullable();
            $table->integer('level_id')->after('package_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('old_notifications', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('package_id');
            $table->dropColumn('level_id');
        });
    }
}
