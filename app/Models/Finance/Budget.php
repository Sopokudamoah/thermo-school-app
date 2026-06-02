<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $table = 'finance_budgets';

    protected $fillable = [
        'name',
        'year',
        'total_allocated',
        'notes',
        'active',
    ];

    protected $casts = [
        'year' => 'integer',
        'total_allocated' => \App\Casts\MoneyCast::class,
        'active' => 'boolean',
    ];

    public function departments()
    {
        return $this->hasMany(BudgetDepartment::class, 'budget_id');
    }

    public function getTotalSpentAttribute()
    {
        $currency = $this->total_allocated->getCurrency();
        $total = new \Money\Money(0, $currency);

        $categoryIds = [];
        foreach ($this->departments as $dept) {
            foreach ($dept->categories as $cat) {
                $categoryIds[] = $cat->expense_category_id;
            }
        }

        if (empty($categoryIds)) {
            return $total;
        }

        $spentRaw = \App\Models\Finance\Expense::whereIn('category_id', $categoryIds)
            ->where('status', \App\Models\Finance\Expense::STATUS_APPROVED)
            ->whereYear('expense_date', $this->year)
            ->sum('amount');

        return new \Money\Money((string)round($spentRaw * 100), $currency);
    }
}
