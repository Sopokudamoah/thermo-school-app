<?php

namespace App\Repositories\Finance;

use App\Interfaces\Finance\PaymentInterface;
use App\Models\Finance\Payment;
use App\Models\Finance\PaymentAllocation;

class PaymentRepository implements PaymentInterface
{
    public function getAll(array $filters = [])
    {
        $query = Payment::with(['student', 'receiver', 'allocations.invoice']);

        if (!empty($filters['student_id'])) {
            $query->where('student_id', $filters['student_id']);
        }
        if (!empty($filters['method'])) {
            $query->where('method', $filters['method']);
        }
        if (!empty($filters['from_date'])) {
            $query->whereDate('payment_date', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->whereDate('payment_date', '<=', $filters['to_date']);
        }

        return $query->orderByDesc('payment_date')->get();
    }

    public function findById($id)
    {
        return Payment::with(['student', 'receiver', 'allocations.invoice'])->findOrFail($id);
    }

    public function getByStudent($student_id)
    {
        return Payment::with(['allocations.invoice'])
            ->where('student_id', $student_id)
            ->orderByDesc('payment_date')
            ->get();
    }

    public function create(array $data, array $allocations)
    {
        $payment = Payment::create($data);

        foreach ($allocations as $allocation) {
            PaymentAllocation::create([
                'payment_id' => $payment->id,
                'invoice_id' => $allocation['invoice_id'],
                'amount' => $allocation['amount'],
            ]);
        }

        return $payment;
    }

    public function generateReceiptNumber()
    {
        $year = date('Y');
        $count = Payment::whereYear('created_at', $year)->count() + 1;
        return 'RCP-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    public function getCollectionSummary(array $filters = [])
    {
        $query = Payment::query();

        if (!empty($filters['from_date'])) {
            $query->whereDate('payment_date', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->whereDate('payment_date', '<=', $filters['to_date']);
        }

        return [
            'total' => $query->sum('amount'),
            'count' => $query->count(),
            'by_method' => $query->groupBy('method')->selectRaw('method, sum(amount) as total, count(*) as count')->get(
            ),
        ];
    }
}
