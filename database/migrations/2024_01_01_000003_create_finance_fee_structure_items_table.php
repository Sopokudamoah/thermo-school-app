<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceFeeStructureItemsTable extends Migration
{
    public function up()
    {
        Schema::create('finance_fee_structure_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('fee_structure_id');
            $table->unsignedInteger('fee_type_id');
            $table->decimal('amount', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_fee_structure_items');
    }
}
