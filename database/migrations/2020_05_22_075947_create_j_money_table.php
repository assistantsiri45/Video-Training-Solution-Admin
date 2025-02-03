<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJMoneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('j_money', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('activity')->comment('1=>SIGN_UP, 2=>FIRST_PURCHASE, 3=>PROMOTIONAL_ACTIVITY,4=>REFERRAL_ACTIVITY,5=>REFUND');
            $table->float('points');
            $table->integer('expire_after');
            $table->timestamp('expire_at');
            $table->softDeletes();
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
