<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->string('email')->unique();
            $table->string('phone', 11);
            // ADDRESS
            $table->string('street');
            $table->string('barangay');
            $table->string('municipality');
            $table->string('city');
            // 
            $table->date('birthdate');
            $table->enum('gender', ['Male','Female']);

            $table->string('shs_school');
            $table->string('shs_course');
            $table->decimal('gwa', 5, 2);

            $table->string('primary_course');
            $table->string('secondary_course')->nullable();
            $table->string('tertiary_course')->nullable();

            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->timestamp('passed_at')->nullable();
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
        Schema::dropIfExists('admissions');
    }
}
