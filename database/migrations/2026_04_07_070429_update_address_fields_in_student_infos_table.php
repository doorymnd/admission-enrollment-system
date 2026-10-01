<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddressFieldsInStudentInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_infos', function (Blueprint $table) {
            $table->string('street')->after('phone');
            $table->string('barangay')->after('street');
            $table->string('municipality')->after('barangay');
            $table->string('city')->after('municipality');

            $table->dropColumn('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_infos', function (Blueprint $table) {
            //
        });
    }
}
