<?php

namespace App\Repositories\Finance;

use App\Interfaces\Finance\BudgetInterface;
use App\Models\Finance\Budget;
use App\Models\Finance\Expense;

class BudgetRepository implements BudgetInterface
{
    public function getAll()
    {
        return Budget::with(['departments.categories.expense_category'])->orderByDesc('year')->get();
    }

    public function findById($id)
    {
        return Budget::with(['departments.categories.expense_category'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Budget::create($data);
    }

    public function update($id, array $data)
    {
        $budget = Budget::findOrFail($id);
        $budget->update($data);
        return $budget;
    }

    public function getVarianceReport($budget_id)
    {
        $budget = $this->findById($budget_id);

        $currency = \App\Helpers\MoneyHelper::getCurrency();
        $zero = \App\Helpers\MoneyHelper::zero();

        $report = [];
        foreach ($budget->departments as $dept) {
            $dept_spent = $zero;
            $categories = [];
            foreach ($dept->categories as $cat) {
                $spentRaw = Expense::where('category_id', $cat->expense_category_id)
                    ->where('status', Expense::STATUS_APPROVED)
                    ->whereYear('expense_date', $budget->year)
                    ->sum('amount');

                $spent = new \Money\Money((string)round($spentRaw * 100), $currency);

                $remaining = $cat->allocated->subtract($spent);

                $allocatedFloat = (float)$cat->allocated->getAmount() / 100;
                $spentFloat = (float)$spent->getAmount() / 100;

                $categories[] = [
                    'name' => $cat->expense_category?->name ?? 'Unknown',
                    'allocated' => $cat->allocated,
                    'spent' => $spent,
                    'remaining' => $remaining,
                    'variance_pct' => $allocatedFloat > 0 ? round(
                        (($allocatedFloat - $spentFloat) / $allocatedFloat) * 100,
                        1
                    ) : 0,
                ];
                $dept_spent = $dept_spent->add($spent);
            }

            $report[] = [
                'name' => $dept->name,
                'allocated' => $dept->allocated,
                'spent' => $dept_spent,
                'remaining' => $dept->allocated->subtract($dept_spent),
                'categories' => $categories,
            ];
        }

        return $report;
    }
}
