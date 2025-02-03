<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('board_id')->nullable();
            $table->foreign('board_id')->references('id')->on('courses')->onDelete('set null');
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->foreign('grade_id')->references('id')->on('levels')->onDelete('set null');
            $table->unsignedBigInteger('event_details')->nullable();
            $table->foreign('event_details')->references('id')->on('instructions')->onDelete('set null');
            $table->unsignedBigInteger('sample_test')->nullable();
            $table->foreign('sample_test')->references('id')->on('test')->onDelete('set null');
            $table->string('name')->nullable();
            $table->string('logo')->nullable();
            $table->string('event_type')->nullable();
            $table->string('access_type')->nullable();
            $table->string('mode')->nullable();
            $table->string('device')->nullable();
            $table->string('rewards')->nullable();
            $table->double('price', 8, 2)->nullable();
            $table->tinyInteger('is_free')->nullable();
            $table->tinyInteger('rounds')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('enroll_start_date')->nullable();
            $table->date('enroll_end_date')->nullable();
            $table->tinyInteger('order_by')->nullable();
            $table->tinyInteger('status')->default(1)->nullable();
            $table->tinyInteger('is_sample')->default(0)->nullable();
            $table->tinyInteger('is_published')->default(0)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('event');
    }
}
