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

        $report = [];
        foreach ($budget->departments as $dept) {
            $dept_spent = 0;
            $categories = [];
            foreach ($dept->categories as $cat) {
                $spent = Expense::where('category_id', $cat->expense_category_id)
                    ->where('status', Expense::STATUS_APPROVED)
                    ->whereYear('expense_date', $budget->year)
                    ->sum('amount');

                $categories[] = [
                    'name' => $cat->expense_category?->name ?? 'Unknown',
                    'allocated' => $cat->allocated,
                    'spent' => $spent,
                    'remaining' => $cat->allocated - $spent,
                    'variance_pct' => $cat->allocated > 0 ? round(
                        (($cat->allocated - $spent) / $cat->allocated) * 100,
                        1
                    ) : 0,
                ];
                $dept_spent += $spent;
            }

            $report[] = [
                'name' => $dept->name,
                'allocated' => $dept->allocated,
                'spent' => $dept_spent,
                'remaining' => $dept->allocated - $dept_spent,
                'categories' => $categories,
            ];
        }

        return $report;
    }
}
