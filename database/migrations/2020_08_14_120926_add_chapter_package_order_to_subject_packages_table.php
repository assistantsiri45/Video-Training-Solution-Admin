<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChapterPackageOrderToSubjectPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subject_packages', function (Blueprint $table) {
            $table->integer('chapter_package_order')->nullable()->after('chapter_package_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subject_packages', function (Blueprint $table) {
            $table->dropColumn('chapter_package_order');
        });
    }
}
