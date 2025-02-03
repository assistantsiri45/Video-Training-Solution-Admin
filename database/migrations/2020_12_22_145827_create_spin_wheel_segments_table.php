<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpinWheelSegmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spin_wheel_segments', function (Blueprint $table) {
            $table->id();
            $table->integer('spin_wheel_campaign_id');
            $table->string('title');
            $table->integer('point');
            $table->integer('color_code');
            $table->integer('hit_number');
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
        Schema::dropIfExists('spin_wheel_segments');
    }
}
