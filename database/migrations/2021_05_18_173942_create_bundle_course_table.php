<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBundleCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundle_course', function (Blueprint $table) {
            $table->id();

            $table->integer("course_id");
            $table->foreign('course_id')->references('id')->on('courses');

            $table->integer("bundle_id");
            $table->foreign('bundle_id')->references('id')->on('courses');

            $table->integer("price")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bundle_course');
    }
}
