<?php

namespace App\Models\Finance;

use App\Models\SchoolSession;
use App\Models\Semester;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'finance_invoices';

    protected $fillable = [
        'invoice_number',
        'student_id',
        'session_id',
        'semester_id',
        'issue_date',
        'due_date',
        'subtotal',
        'discount_amount',
        'scholarship_amount',
        'total',
        'paid_amount',
        'balance',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => \App\Casts\MoneyCast::class,
        'discount_amount' => \App\Casts\MoneyCast::class,
        'scholarship_amount' => \App\Casts\MoneyCast::class,
        'total' => \App\Casts\MoneyCast::class,
        'paid_amount' => \App\Casts\MoneyCast::class,
        'balance' => \App\Casts\MoneyCast::class,
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_PARTIALLY_PAID = 'partially_paid';
    public const STATUS_PAID = 'paid';
    public const STATUS_OVERDUE = 'overdue';
    public const STATUS_CANCELLED = 'cancelled';

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function session()
    {
        return $this->belongsTo(SchoolSession::class, 'session_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    public function invoice_discounts()
    {
        return $this->hasMany(InvoiceDiscount::class, 'invoice_id');
    }

    public function payment_allocations()
    {
        return $this->hasMany(PaymentAllocation::class, 'invoice_id');
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class, 'finance_payment_allocations', 'invoice_id', 'payment_id')
            ->withPivot('amount')
            ->withTimestamps();
    }

    public function student_scholarships()
    {
        return $this->hasMany(StudentScholarship::class, 'invoice_id');
    }
}
