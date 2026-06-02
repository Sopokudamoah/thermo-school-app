<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceInvoiceDiscountsTable extends Migration
{
    public function up()
    {
        Schema::create('finance_invoice_discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('discount_id');
            $table->decimal('amount_applied', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_invoice_discounts');
    }
}
