<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddressFieldsInAdmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admissions', function (Blueprint $table) {
            // add new separate fields
            $table->string('street')->after('phone');
            $table->string('barangay')->after('street');
            $table->string('municipality')->after('barangay');
            $table->string('city')->after('municipality');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admissions', function (Blueprint $table) {


        });
    }
}
