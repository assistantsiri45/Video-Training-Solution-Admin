<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToJMoneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('j_money', function (Blueprint $table) {
            $table->tinyInteger('is_used')->default(0)->comment("0=> NOT USED, 1=> USED")->after('expire_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('j_money', function (Blueprint $table) {
            $table->tinyInteger('is_used')->default(0)->comment("0=> NOT USED, 1=> USED")->after('expire_at');
        });
    }
}
