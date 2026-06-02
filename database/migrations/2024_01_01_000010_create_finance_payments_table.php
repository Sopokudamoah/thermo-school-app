<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('finance_payments', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->unsignedInteger('student_id');
            $table->date('payment_date');
            $table->decimal('amount', 12, 2);
            $table->string('method'); // cash, bank_transfer, card, online_gateway, mobile_money
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('received_by');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_payments');
    }
}
