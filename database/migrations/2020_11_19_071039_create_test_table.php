<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instruction_id')->nullable();
            $table->foreign('instruction_id')->references('id')->on('instructions')->onDelete('set null');
            $table->unsignedBigInteger('board_id')->nullable();
            $table->foreign('board_id')->references('id')->on('courses')->onDelete('set null');
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->foreign('grade_id')->references('id')->on('levels')->onDelete('set null');
            $table->string('name')->nullable();
            $table->string('test_type')->nullable();
            $table->integer('sections')->nullable();
            $table->string('ques_selection_type')->nullable();
            $table->string('auto_selection_type')->nullable();
            $table->string('is_difficulty')->nullable();
            $table->integer('attempt')->nullable();
            $table->float('negative')->nullable();
            $table->tinyInteger('is_feedback')->default(0)->nullable();
            $table->string('feedback_type')->nullable();
            $table->tinyInteger('ques_ordered')->nullable();
            $table->tinyInteger('ans_ordered')->nullable();
            $table->tinyInteger('status')->default(1)->nullable();
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
        Schema::dropIfExists('test');
    }
}
