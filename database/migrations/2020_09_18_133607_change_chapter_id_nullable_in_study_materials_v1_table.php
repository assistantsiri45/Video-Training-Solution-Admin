<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeChapterIdNullableInStudyMaterialsV1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('study_materials_v1', function (Blueprint $table) {
            $table->integer('chapter_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('study_materials_v1', function (Blueprint $table) {
            $table->integer('chapter_id')->nullable()->change();
        });
    }
}
