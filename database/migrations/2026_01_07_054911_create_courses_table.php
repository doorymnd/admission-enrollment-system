<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('program_id')
                ->constrained('programs')
                ->onDelete('cascade');

            $table->string('code');   // BSIT, BSEntrep
            $table->string('name');   // Bachelor of Science in IT
            $table->unsignedTinyInteger('duration_years');
            $table->string('section_code');       // B1, IT, HM
            $table->unsignedTinyInteger('sections_per_year')->default(3);
            $table->unsignedSmallInteger('max_students_per_section')->default(30);

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
        Schema::dropIfExists('courses');
    }
}
