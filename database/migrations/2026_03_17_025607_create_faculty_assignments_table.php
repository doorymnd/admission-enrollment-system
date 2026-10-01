<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacultyAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculty_assignments', function (Blueprint $table) {
                        $table->id();

            $table->unsignedBigInteger('faculty_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('subject_id');

            $table->integer('year_level');
            $table->integer('semester');
            $table->string('section');

            $table->timestamps();

            $table->unique(
                ['faculty_id','course_id','subject_id','year_level','semester','section'],
                'faculty_assign_unique'
            );

            // Foreign Keys
            $table->foreign('faculty_id')->references('id')->on('faculties')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faculty_assignments');
    }
}
