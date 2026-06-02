<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetCategory extends Model
{
    use HasFactory;

    protected $table = 'finance_budget_categories';

    protected $fillable = [
        'budget_department_id',
        'expense_category_id',
        'allocated',
    ];

    protected $casts = [
        'allocated' => 'decimal:2',
    ];

    public function department()
    {
        return $this->belongsTo(BudgetDepartment::class, 'budget_department_id');
    }

    public function expense_category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }
}
