<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('student_subjects', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->onDelete('cascade');

            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->onDelete('cascade');

            $table->foreignId('course_id')
                ->nullable()
                ->constrained('courses')
                ->onDelete('set null');

            $table->string('year_level')->nullable();
            $table->string('semester')->nullable();
            $table->string('school_year')->nullable();

            $table->string('status')->default('pending');

            $table->timestamps();

            $table->unique(
                ['student_id', 'subject_id', 'semester', 'school_year'],
                'unique_student_subject'
            );
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_subjects');
    }
}
