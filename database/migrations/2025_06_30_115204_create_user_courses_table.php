<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_courses', function (Blueprint $table) {
            $table->id();
                $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('course_id');
    $table->timestamp('enrolled_at')->useCurrent();
    $table->date('expire_date')->nullable(); 

    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
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
        Schema::dropIfExists('user_courses');
    }
}
