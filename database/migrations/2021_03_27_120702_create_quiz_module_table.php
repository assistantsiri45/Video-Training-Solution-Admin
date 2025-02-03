<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_mst', function (Blueprint $table) {
            $table->id();
            $table->integer('subject_id')->nullable();
            $table->integer('chapter_id')->nullable();;
            $table->string('name')->nullable();
            $table->integer('no_of_ques')->nullable();
            $table->bigInteger('time')->nullable();
            $table->integer('easy_ques')->nullable();
            $table->integer('medium_ques')->nullable();
            $table->integer('hard_ques')->nullable();
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
        Schema::dropIfExists('module_mst');
    }
}
