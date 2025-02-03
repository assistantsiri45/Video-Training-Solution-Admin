<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommissionsToAssociatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('associates', function (Blueprint $table) {
            $table->dropColumn('commission');
            $table->string('commission_new_student')->nullable()->after('address');
            $table->string('commission_repeat_purchase_by_agent')->nullable()->after('commission_new_student');
            $table->string('commission_repeat_purchase_by_student')->nullable()->after('commission_repeat_purchase_by_agent');
            $table->string('commission_repeat_purchase_by_other_agent')->nullable()->after('commission_repeat_purchase_by_student');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('associates', function (Blueprint $table) {
            $table->string('commission')->nullable()->after('address');
            $table->dropColumn('commission_new_student');
            $table->dropColumn('commission_repeat_purchase_by_agent');
            $table->dropColumn('commission_repeat_purchase_by_student');
            $table->dropColumn('commission_repeat_purchase_by_other_agent');
        });
    }
}
