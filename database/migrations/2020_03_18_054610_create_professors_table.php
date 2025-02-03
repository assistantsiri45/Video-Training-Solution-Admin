<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professors', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->string('email');
            $table->string('mobile');
            $table->tinyInteger('password_option')->default(1)->comment("1=> MAIL, 2=> MANUAL");
            $table->tinyInteger('video_type')->default(1)->comment("1=>MANUAL_UPLOAD , 2=> YOUTUBE");
            $table->string('media_id')->nullable();
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->string('qualification')->nullable();
            $table->string('experience')->nullable();
            $table->string('professor_revenue')->nullable();
            $table->longText('introduction')->nullable();
            $table->longText('description')->nullable();
            $table->double('rating')->nullable();
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
        Schema::dropIfExists('professors');
    }
}
