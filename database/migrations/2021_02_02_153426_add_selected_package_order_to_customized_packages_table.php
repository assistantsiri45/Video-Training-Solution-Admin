<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSelectedPackageOrderToCustomizedPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customized_packages', function (Blueprint $table) {
            $table->integer('selected_package_order')->nullable()->after('selected_package_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customized_packages', function (Blueprint $table) {
            $table->dropColumn('selected_package_order');
        });
    }
}
