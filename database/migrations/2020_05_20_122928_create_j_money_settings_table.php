<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJMoneySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('j_money_settings', function (Blueprint $table) {
            $table->id();
            $table->float('sign_up_point')->nullable();
            $table->integer('sign_up_point_expiry')->nullable();
            $table->float('first_purchase_point')->nullable();
            $table->integer('first_purchase_point_expiry')->nullable();
            $table->float('promotional_activity_point')->nullable();
            $table->integer('promotional_activity_point_expiry')->nullable();
            $table->float('referral_activity_point')->nullable();
            $table->integer('referral_activity_point_expiry')->nullable();
            $table->integer('refund_expiry')->nullable();
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
        Schema::dropIfExists('j_money');
    }
}
