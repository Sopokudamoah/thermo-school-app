<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceAuditLogsTable extends Migration
{
    public function up()
    {
        Schema::create('finance_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('action');
            $table->string('auditable_type');
            $table->unsignedInteger('auditable_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_audit_logs');
    }
}
