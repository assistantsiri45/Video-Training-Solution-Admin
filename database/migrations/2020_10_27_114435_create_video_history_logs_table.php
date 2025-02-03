<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoHistoryLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_history_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('video_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('package_id')->nullable();
            $table->integer('order_item_id')->nullable();
            $table->integer('duration')->nullable();
            $table->integer('total_duration')->nullable();
            $table->integer('position')->nullable();
            $table->string('browser_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video_history_logs');
    }
}
