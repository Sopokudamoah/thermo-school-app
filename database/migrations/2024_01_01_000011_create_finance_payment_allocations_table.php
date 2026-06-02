<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancePaymentAllocationsTable extends Migration
{
    public function up()
    {
        Schema::create('finance_payment_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('payment_id');
            $table->unsignedInteger('invoice_id');
            $table->decimal('amount', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_payment_allocations');
    }
}
