<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->unsigned();
            $table->integer('level_id')->unsigned();
            $table->integer('subject_id')->unsigned();
            $table->integer('chapter_id')->unsigned();
            $table->integer('professor_id')->unsigned();
            $table->string('title');
            $table->string('thumbnail')->nullable();
            $table->longText('url')->nullable();
            $table->string('media_id')->nullable();
            $table->string('session');
            $table->integer('duration')->nullable();
            $table->integer('order')->nullable();
            $table->longText('description')->nullable();
            $table->longText('tags')->nullable();
            $table->boolean('is_demo');
            $table->integer('start_time')->nullable();
            $table->integer('end_time')->nullable();
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
        Schema::dropIfExists('videos');
    }
}
