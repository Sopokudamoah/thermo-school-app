<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGeneralSettingsToAcademicSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('academic_settings', function (Blueprint $table) {
            $table->string('school_name')->nullable();
            $table->text('school_address')->nullable();
            $table->string('school_phone')->nullable();
            $table->string('school_email')->nullable();
            $table->string('logo')->nullable();
            $table->string('currency_symbol')->default('₵');
            $table->string('currency_code')->default('GHS');
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
            $table->dropColumn([
                'school_name',
                'school_address',
                'school_phone',
                'school_email',
                'logo',
                'currency_symbol',
                'currency_code'
            ]);
        });
    }
}
