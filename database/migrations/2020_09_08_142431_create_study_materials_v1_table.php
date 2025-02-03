<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudyMaterialsV1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_materials_v1', function (Blueprint $table) {
            $table->id();
            $table->string('course_id');
            $table->string('level_id');
            $table->string('subject_id');
            $table->string('chapter_id');
            $table->string('language_id');
            $table->string('professor_id');
            $table->string('type');
            $table->string('file_name');
            $table->string('title');
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
        Schema::dropIfExists('study_materials_v1');
    }
}
