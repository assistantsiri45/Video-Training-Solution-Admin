<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->longText('title')->nullable();
            $table->longText('mail_notification_body')->nullable();
            $table->longText('notification_body')->nullable();
            $table->integer('count')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('type')->nullable();
            $table->integer('package_id')->nullable();
            $table->integer('level_id')->nullable();
            $table->dateTime('expire_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
