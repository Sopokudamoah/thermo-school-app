<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $table = 'finance_expense_categories';

    protected $fillable = [
        'name',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'category_id');
    }

    public function budget_categories()
    {
        return $this->hasMany(BudgetCategory::class, 'expense_category_id');
    }
}
