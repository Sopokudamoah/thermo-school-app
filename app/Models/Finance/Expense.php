<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'finance_expenses';

    protected $fillable = [
        'title',
        'category_id',
        'vendor_id',
        'amount',
        'expense_date',
        'description',
        'receipt_path',
        'status',
        'submitted_by',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'approved_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
