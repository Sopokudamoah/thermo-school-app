<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStudentForeignKeysToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tables that reference student_id
        $tables = [
            'promotions',
            'marks',
            'final_marks',
            'attendances',
            'student_parent_infos',
            'student_academic_infos',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                // We are not adding actual database constraints because they might not have existed
                // and we don't want to break existing data migration logic if it's run manually.
                // But we are documenting that student_id now refers to the students table.
                $table->unsignedBigInteger('student_id')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = [
            'promotions',
            'marks',
            'final_marks',
            'attendances',
            'student_parent_infos',
            'student_academic_infos',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedInteger('student_id')->change();
            });
        }
    }
}
