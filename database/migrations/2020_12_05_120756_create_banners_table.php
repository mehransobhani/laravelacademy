<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();

            $table->integer('course_id')->nullable();
            $table->foreign('course_id')->references('id')->on('courses');

            $table->integer('art_id')->nullable();
            $table->foreign('art_id')->references('id')->on('arts');

            $table->enum('type', ['most-popular', 'our-offer']);
            $table->integer('position');
            $table->string('img')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
