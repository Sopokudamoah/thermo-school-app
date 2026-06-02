<?php

namespace App\Repositories\Finance;

use App\Interfaces\Finance\ExpenseInterface;
use App\Models\Finance\Expense;
use Carbon\Carbon;

class ExpenseRepository implements ExpenseInterface
{
    public function getAll(array $filters = [])
    {
        $query = Expense::with(['category', 'vendor', 'submitter', 'approver']);

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['from_date'])) {
            $query->whereDate('expense_date', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->whereDate('expense_date', '<=', $filters['to_date']);
        }

        return $query->orderByDesc('expense_date')->get();
    }

    public function findById($id)
    {
        return Expense::with(['category', 'vendor', 'submitter', 'approver'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Expense::create($data);
    }

    public function update($id, array $data)
    {
        $expense = Expense::findOrFail($id);
        $expense->update($data);
        return $expense;
    }

    public function approve($id, $approved_by)
    {
        $expense = Expense::findOrFail($id);
        $expense->update([
            'status' => Expense::STATUS_APPROVED,
            'approved_by' => $approved_by,
            'approved_at' => Carbon::now(),
            'rejection_reason' => null,
        ]);
        return $expense;
    }

    public function reject($id, $reason)
    {
        $expense = Expense::findOrFail($id);
        $expense->update([
            'status' => Expense::STATUS_REJECTED,
            'rejection_reason' => $reason,
        ]);
        return $expense;
    }

    public function getByCategory(array $filters = [])
    {
        return Expense::with('category')
            ->where('status', Expense::STATUS_APPROVED)
            ->when(!empty($filters['from_date']), fn($q) => $q->whereDate('expense_date', '>=', $filters['from_date']))
            ->when(!empty($filters['to_date']), fn($q) => $q->whereDate('expense_date', '<=', $filters['to_date']))
            ->groupBy('category_id')
            ->selectRaw('category_id, sum(amount) as total, count(*) as count')
            ->get();
    }

    public function getSummary(array $filters = [])
    {
        $query = Expense::where('status', Expense::STATUS_APPROVED);

        if (!empty($filters['from_date'])) {
            $query->whereDate('expense_date', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->whereDate('expense_date', '<=', $filters['to_date']);
        }

        return [
            'total' => $query->sum('amount'),
            'count' => $query->count(),
        ];
    }
}
