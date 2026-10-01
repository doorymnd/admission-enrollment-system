<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();

            $table->string('phone')->nullable();
            
            $table->string('street');
            $table->string('barangay');
            $table->string('municipality');
            $table->string('city');

            $table->date('birthdate')->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();

            $table->string('shs_school');
            $table->string('shs_course');
            $table->decimal('gwa', 5, 2);

            $table->string('profile_picture')->nullable();

            $table->enum('edit_request_status', ['none', 'pending', 'approved', 'rejected'])
                ->default('none');

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
        Schema::dropIfExists('student_infos');
    }
}
