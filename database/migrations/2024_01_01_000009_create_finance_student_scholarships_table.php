<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceStudentScholarshipsTable extends Migration
{
    public function up()
    {
        Schema::create('finance_student_scholarships', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('scholarship_id');
            $table->unsignedInteger('invoice_id')->nullable();
            $table->date('approval_date');
            $table->unsignedInteger('approved_by');
            $table->date('valid_from');
            $table->date('valid_until')->nullable();
            $table->decimal('amount_applied', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('status')->default('active'); // active, expired, revoked
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_student_scholarships');
    }
}
