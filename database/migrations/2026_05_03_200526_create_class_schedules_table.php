<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');

            $table->string('section'); // e.g. BSIT-1A
            $table->foreignId('teacher_id')->nullable();

            $table->string('semester'); // 1st / 2nd
            $table->string('school_year'); // 2026-2027

            $table->string('day'); // Mon, Tue, etc.
            $table->time('start_time');
            $table->time('end_time');

            $table->string('room')->nullable();

            $table->integer('capacity')->default(30);

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
        Schema::dropIfExists('class_schedules');
    }
}
