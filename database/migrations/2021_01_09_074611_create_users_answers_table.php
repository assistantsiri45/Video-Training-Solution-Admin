<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_test_id')->nullable();
            $table->foreign('user_test_id')->references('id')->on('user_test')->onDelete('set null');
            $table->unsignedBigInteger('user_question_id')->nullable();
            $table->foreign('user_question_id')->references('id')->on('user_questions')->onDelete('set null');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('question_id')->nullable();
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('set null');
            $table->unsignedBigInteger('option_id')->nullable();
            $table->foreign('option_id')->references('id')->on('question_answers')->onDelete('set null');
            $table->tinyInteger('is_correct')->default(0)->nullable();
            $table->integer('esec')->nullable();
            $table->integer('rsec')->nullable();
            $table->integer('mil')->nullable();
            $table->tinyInteger('status')->default(1)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('users_answers');
    }
}
