<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiveSemesterIdToAcademicSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('academic_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('active_semester_id')->nullable()->after('marks_submission_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('academic_settings', function (Blueprint $table) {
            $table->dropColumn('active_semester_id');
        });
    }
}
