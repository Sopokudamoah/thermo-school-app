<?php

namespace App\Models\Finance;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentScholarship extends Model
{
    use HasFactory;

    protected $table = 'finance_student_scholarships';

    protected $fillable = [
        'student_id',
        'scholarship_id',
        'invoice_id',
        'approval_date',
        'approved_by',
        'valid_from',
        'valid_until',
        'amount_applied',
        'notes',
        'status',
    ];

    protected $casts = [
        'approval_date' => 'date',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'amount_applied' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
