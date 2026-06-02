<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceInvoiceItemsTable extends Migration
{
    public function up()
    {
        Schema::create('finance_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('fee_type_id');
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_invoice_items');
    }
}
