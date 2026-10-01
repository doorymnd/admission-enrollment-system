<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToStudentSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_subjects', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->after('subject_id');
            $table->integer('year_level')->nullable();
            $table->integer('semester')->nullable();
            $table->string('school_year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_subjects', function (Blueprint $table) {
            //
        });
    }
}
