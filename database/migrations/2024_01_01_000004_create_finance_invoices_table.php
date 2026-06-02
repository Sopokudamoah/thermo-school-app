<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('finance_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('session_id');
            $table->unsignedInteger('semester_id')->nullable();
            $table->date('issue_date');
            $table->date('due_date');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('scholarship_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);
            $table->string('status')->default('draft'); // draft, pending, partially_paid, paid, overdue, cancelled
            $table->text('notes')->nullable();
            $table->unsignedInteger('created_by');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_invoices');
    }
}
