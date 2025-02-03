<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToStudyMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('study_materials', function (Blueprint $table) {
            $table->string('course_id')->nullable()->after('id');
            $table->string('level_id')->nullable()->after('course_id');
            $table->string('subject_id')->nullable()->after('level_id');
            $table->string('language_id')->nullable()->after('chapter_id');
            $table->string('professor_id')->nullable()->after('language_id');
            $table->string('type')->nullable()->after('professor_id');
            $table->string('title')->nullable()->after('file_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('study_materials', function (Blueprint $table) {
            //
        });
    }
}
