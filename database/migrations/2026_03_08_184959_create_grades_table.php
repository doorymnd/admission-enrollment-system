<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('assignment_id')->nullable()->constrained('faculty_assignments')->onDelete('cascade');
            $table->foreignId('faculty_id')->constrained('faculties')->onDelete('cascade');

            $table->string('course');
            $table->string('section');
            $table->integer('year_level');
            $table->integer('semester');
            $table->string('school_year');

            $table->decimal('raw_grade',5,2)->nullable()->after('school_year');
            $table->decimal('final_grade',4,2)->nullable()->after('raw_grade');

            $table->enum('status',[
                'pending',
                'submitted',
                'approved',
                'released',
                'edit_request'
            ])->default('pending');
            

            $table->string('type')->default('regular');

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
        Schema::dropIfExists('grades');
    }
}
