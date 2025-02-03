<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('board_id')->nullable();
            $table->foreign('board_id')->references('id')->on('courses')->onDelete('set null');
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->foreign('grade_id')->references('id')->on('levels')->onDelete('set null');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
            $table->unsignedBigInteger('chapter_id')->nullable();
            $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('set null');
            $table->unsignedBigInteger('concept_id')->nullable();
            $table->foreign('concept_id')->references('id')->on('concept_mst')->onDelete('set null');
            $table->unsignedBigInteger('instruction_id')->nullable();
            $table->foreign('instruction_id')->references('id')->on('instructions')->onDelete('set null');
            $table->unsignedBigInteger('paragraph_id')->nullable();
            $table->foreign('paragraph_id')->references('id')->on('paragraph')->onDelete('set null');
            $table->string('question_type')->nullable();
            $table->string('content_type')->nullable();
            $table->text('question')->nullable();
            $table->text('question_desc')->nullable();
            $table->string('attachment')->nullable();
            $table->string('difficulty')->nullable();
            $table->integer('score')->nullable();
            $table->integer('negative')->nullable();
            $table->integer('time')->nullable();
            $table->text('correct_feedback')->nullable();
            $table->text('incorrect_feedback')->nullable();
            $table->text('partially_feedback')->nullable();
            $table->tinyInteger('no_of_options')->nullable();
            $table->tinyInteger('is_paragraph')->nullable();
            $table->tinyInteger('order_by')->nullable();
            $table->tinyInteger('status')->default(1)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('questions');
    }
}
