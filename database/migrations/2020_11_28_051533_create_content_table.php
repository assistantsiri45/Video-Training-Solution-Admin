<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_library', function (Blueprint $table) {
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
            $table->string('name')->nullable();
            $table->string('content_type')->default(50)->nullable();
            $table->string('content_original_name')->nullable();
            $table->text('thumbnail')->nullable();
            $table->text('url')->nullable();
            $table->unsignedBigInteger('taxonomy_id')->nullable();
            $table->foreign('taxonomy_id')->references('id')->on('taxonomy_mst')->onDelete('set null');
            $table->unsignedBigInteger('learning_stage_id')->nullable();
            $table->foreign('learning_stage_id')->references('id')->on('learning_stage_mst')->onDelete('set null');
            $table->tinyInteger('status')->default(1)->nullable();
            $table->integer('added_by')->nullable();
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
        Schema::dropIfExists('content_library');
    }
}
