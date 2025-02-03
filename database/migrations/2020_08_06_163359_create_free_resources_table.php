<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreeResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('free_resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('professor_id');
            $table->string('description');
            $table->string('type');
            $table->string('file')->nullable();
            $table->string('media_id')->nullable();
            $table->string('video')->nullable();
            $table->string('youtube_id')->nullable();
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
        Schema::dropIfExists('free_resources');
    }
}
