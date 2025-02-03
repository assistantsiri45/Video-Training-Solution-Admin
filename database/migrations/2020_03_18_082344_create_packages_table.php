<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->integer('type');
            $table->integer('category');
            $table->string('name');
            $table->integer('course_id');
            $table->integer('level_id');
            $table->integer('subject_id');
            $table->integer('chapter_id');
            $table->integer('language_id');
            $table->double('price');
            $table->double('discounted_price')->nullable();
            $table->timestamp('discounted_price_expire_at')->nullable();
            $table->double('special_price')->nullable();
            $table->timestamp('special_price_expire_at')->nullable();
            $table->string('image')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('is_mini');
            $table->boolean('is_crash_course');
            $table->boolean('is_approved')->default(false);
            $table->integer('approved_user_id')->nullable();
            $table->double('professor_revenue')->default(10)->nullable();
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
        Schema::dropIfExists('packages');
    }
}
