<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceFeeStructuresTable extends Migration
{
    public function up()
    {
        Schema::create('finance_fee_structures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('session_id');
            $table->unsignedInteger('semester_id')->nullable();
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('section_id')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_fee_structures');
    }
}
