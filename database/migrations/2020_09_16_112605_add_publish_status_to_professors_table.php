<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublishStatusToProfessorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('professors', function (Blueprint $table) {
            $table->tinyInteger('publish_status')->default(0)->comment("0=> UNPUBLISHED, 1=> PUBLISHED")->after('video');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('professors', function (Blueprint $table) {
            $table->tinyInteger('publish_status')->default(0)->comment("0=> UNPUBLISHED, 1=> PUBLISHED")->after('video');
        });
    }
}
