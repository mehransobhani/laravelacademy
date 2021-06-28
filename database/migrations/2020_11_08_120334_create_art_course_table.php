<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('art_course', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->integer('art_id');
            $table->foreign('art_id')->references('id')->on('arts');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('art_course');
    }
}
