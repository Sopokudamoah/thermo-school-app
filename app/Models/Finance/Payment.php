<?php

namespace App\Models\Finance;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'finance_payments';

    protected $fillable = [
        'receipt_number',
        'student_id',
        'payment_date',
        'amount',
        'method',
        'reference',
        'notes',
        'received_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public const METHOD_CASH = 'cash';
    public const METHOD_BANK_TRANSFER = 'bank_transfer';
    public const METHOD_CARD = 'card';
    public const METHOD_ONLINE_GATEWAY = 'online_gateway';
    public const METHOD_MOBILE_MONEY = 'mobile_money';

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function allocations()
    {
        return $this->hasMany(PaymentAllocation::class, 'payment_id');
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'finance_payment_allocations', 'payment_id', 'invoice_id')
            ->withPivot('amount')
            ->withTimestamps();
    }
}
