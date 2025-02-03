<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdugulpVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edugulp_videos', function (Blueprint $table) {
            $table->id();
            $table->string('course');
            $table->string('level');
            $table->string('subject');
            $table->string('chapter');
            $table->string('module')->nullable();
            $table->string('professor');
            $table->string('title');
            $table->text('url');
            $table->string('media_id')->nullable();
            $table->string('session')->nullable();
            $table->string('duration')->nullable();
            $table->text('description');
            $table->string('tags');
            $table->string('language');
            $table->tinyInteger('has_demo')->nullable();
            $table->string('demo_media_id')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->tinyInteger('is_merged')->default(0)->comment("0=> NOT MERGED, 1=> MERGED");
            $table->integer('video_id')->nullable(); //edugulp video table primary key
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
        Schema::dropIfExists('edugulp_videos');
    }
}
