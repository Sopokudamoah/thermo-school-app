<?php

namespace App\Repositories\Finance;

use App\Interfaces\Finance\InvoiceInterface;
use App\Models\Finance\Invoice;
use Carbon\Carbon;

class InvoiceRepository implements InvoiceInterface
{
    public function getAll(array $filters = [])
    {
        $query = Invoice::with(['student', 'session', 'items.fee_type']);

        if (!empty($filters['student_id'])) {
            $query->where('student_id', $filters['student_id']);
        }
        if (!empty($filters['session_id'])) {
            $query->where('session_id', $filters['session_id']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['from_date'])) {
            $query->whereDate('issue_date', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->whereDate('issue_date', '<=', $filters['to_date']);
        }

        return $query->orderByDesc('created_at')->get();
    }

    public function findById($id)
    {
        return Invoice::with([
            'student',
            'session',
            'semester',
            'items.fee_type',
            'invoice_discounts.discount',
            'student_scholarships.scholarship',
            'payment_allocations.payment',
        ])->findOrFail($id);
    }

    public function findByNumber($invoice_number)
    {
        return Invoice::where('invoice_number', $invoice_number)->firstOrFail();
    }

    public function getByStudent($student_id)
    {
        return Invoice::with(['items.fee_type', 'payment_allocations.payment'])
            ->where('student_id', $student_id)
            ->orderByDesc('issue_date')
            ->get();
    }

    public function create(array $data)
    {
        return Invoice::create($data);
    }

    public function update($id, array $data)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update($data);
        return $invoice;
    }

    public function updateStatus($id, $status)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update(['status' => $status]);
        return $invoice;
    }

    public function generateInvoiceNumber()
    {
        $year = date('Y');
        $count = Invoice::whereYear('created_at', $year)->count() + 1;
        return 'INV-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    public function recalculateTotals($id)
    {
        $invoice = Invoice::with(['items', 'invoice_discounts', 'student_scholarships', 'payment_allocations']
        )->findOrFail($id);

        $currency = \App\Helpers\MoneyHelper::getCurrency();
        $zero = \App\Helpers\MoneyHelper::zero();

        $subtotal = $invoice->items->reduce(function ($carry, $item) use ($zero) {
            return $carry->add($item->amount ?? $zero);
        }, $zero);

        $discount_amount = $invoice->invoice_discounts->reduce(function ($carry, $discount) use ($zero) {
            return $carry->add($discount->amount_applied ?? $zero);
        }, $zero);

        $scholarship_amount = $invoice->student_scholarships->reduce(function ($carry, $scholarship) use ($zero) {
            return $carry->add($scholarship->amount_applied ?? $zero);
        }, $zero);

        $total = $subtotal->subtract($discount_amount)->subtract($scholarship_amount);
        if ($total->isNegative()) {
            $total = $zero;
        }

        $paid_amount = $invoice->payment_allocations->reduce(function ($carry, $allocation) use ($zero) {
            return $carry->add($allocation->amount ?? $zero);
        }, $zero);

        $balance = $total->subtract($paid_amount);
        if ($balance->isNegative()) {
            $balance = $zero;
        }

        $status = $this->computeStatus($invoice, $total, $paid_amount, $balance);

        $invoice->update([
            'subtotal' => $subtotal,
            'discount_amount' => $discount_amount,
            'scholarship_amount' => $scholarship_amount,
            'total' => $total,
            'paid_amount' => $paid_amount,
            'balance' => $balance,
            'status' => $status,
        ]);

        return $invoice->fresh();
    }

    private function computeStatus($invoice, $total, $paid_amount, $balance)
    {
        if ($invoice->status === Invoice::STATUS_CANCELLED) {
            return Invoice::STATUS_CANCELLED;
        }
        if ($total->isZero() || $paid_amount->greaterThanOrEqual($total)) {
            return Invoice::STATUS_PAID;
        }
        if (!$paid_amount->isZero()) {
            return Invoice::STATUS_PARTIALLY_PAID;
        }
        if ($invoice->due_date < Carbon::today()) {
            return Invoice::STATUS_OVERDUE;
        }
        return Invoice::STATUS_PENDING;
    }

    public function getOutstandingByStudent($student_id)
    {
        return Invoice::where('student_id', $student_id)
            ->whereIn('status', [Invoice::STATUS_PENDING, Invoice::STATUS_PARTIALLY_PAID, Invoice::STATUS_OVERDUE])
            ->get();
    }

    public function getAgingReport()
    {
        $today = Carbon::today();
        $invoices = Invoice::with('student')
            ->whereIn('status', [Invoice::STATUS_PENDING, Invoice::STATUS_PARTIALLY_PAID, Invoice::STATUS_OVERDUE])
            ->where('balance', '>', 0)
            ->get();

        $buckets = [
            '0_30' => collect(),
            '31_60' => collect(),
            '61_90' => collect(),
            '90_plus' => collect(),
        ];

        foreach ($invoices as $invoice) {
            $days = $today->diffInDays($invoice->due_date, false) * -1;
            if ($days <= 30) {
                $buckets['0_30']->push($invoice);
            } elseif ($days <= 60) {
                $buckets['31_60']->push($invoice);
            } elseif ($days <= 90) {
                $buckets['61_90']->push($invoice);
            } else {
                $buckets['90_plus']->push($invoice);
            }
        }

        return $buckets;
    }
}
