<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceExpensesTable extends Migration
{
    public function up()
    {
        Schema::create('finance_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('vendor_id')->nullable();
            $table->decimal('amount', 12, 2);
            $table->date('expense_date');
            $table->text('description')->nullable();
            $table->string('receipt_path')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->unsignedInteger('submitted_by');
            $table->unsignedInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_expenses');
    }
}
