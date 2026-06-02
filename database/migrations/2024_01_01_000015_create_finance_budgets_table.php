<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceBudgetsTable extends Migration
{
    public function up()
    {
        Schema::create('finance_budgets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('year');
            $table->decimal('total_allocated', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_budgets');
    }
}
