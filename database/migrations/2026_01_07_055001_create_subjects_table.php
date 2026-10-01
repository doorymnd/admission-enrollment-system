    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('code');               // ENT101
            $table->string('name');               // Entrepreneurship I
            $table->unsignedTinyInteger('year_level'); // 1–4
            $table->unsignedTinyInteger('semester');   // 1,2,3 (3 = SUMMER)
            $table->unsignedTinyInteger('units');

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
        Schema::dropIfExists('subjects');
    }
}
