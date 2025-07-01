<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
                        $table->string('userlogin')->nullable()->after('id');
            $table->boolean('phone_validated')->default(false)->after('phone');
            $table->boolean('email_validated')->default(false)->after('email_verified_at');
            $table->unsignedBigInteger('created_by')->nullable()->after('remember_token');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
                        $table->dropColumn([
                'userlogin',
                'phone_validated',
                'email_validated',
                'created_by',
                'updated_by'
            ]);
        });
    }
}
