<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceBudgetCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('finance_budget_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('budget_department_id');
            $table->unsignedInteger('expense_category_id');
            $table->decimal('allocated', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_budget_categories');
    }
}
