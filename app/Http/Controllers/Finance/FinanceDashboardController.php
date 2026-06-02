<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Interfaces\Finance\ExpenseInterface;
use App\Interfaces\Finance\InvoiceInterface;
use App\Interfaces\Finance\PaymentInterface;
use App\Models\Finance\Invoice;
use Carbon\Carbon;

class FinanceDashboardController extends Controller
{
    protected $invoiceRepository;
    protected $paymentRepository;
    protected $expenseRepository;

    public function __construct(
        InvoiceInterface $invoiceRepository,
        PaymentInterface $paymentRepository,
        ExpenseInterface $expenseRepository
    ) {
        $this->middleware(['can:finance.view']);
        $this->invoiceRepository = $invoiceRepository;
        $this->paymentRepository = $paymentRepository;
        $this->expenseRepository = $expenseRepository;
    }

    public function index()
    {
        $year = date('Y');
        $month = date('m');

        $fees_billed = Invoice::whereYear('issue_date', $year)->sum('total');
        $fees_collected = Invoice::whereYear('issue_date', $year)->sum('paid_amount');
        $outstanding = Invoice::whereIn('status', [
            Invoice::STATUS_PENDING,
            Invoice::STATUS_PARTIALLY_PAID,
            Invoice::STATUS_OVERDUE,
        ])->sum('balance');

        $expense_summary = $this->expenseRepository->getSummary([
            'from_date' => "$year-01-01",
            'to_date' => "$year-12-31",
        ]);
        $expenses = $expense_summary['total'];
        $net_position = $fees_collected - $expenses;

        $collection_trend = $this->getMonthlyCollectionTrend($year);
        $expense_trend = $this->getMonthlyExpenseTrend($year);
        $revenue_by_fee_type = $this->getRevenueByFeeType($year);

        return view(
            'finance.dashboard',
            compact(
                'fees_billed',
                'fees_collected',
                'outstanding',
                'expenses',
                'net_position',
                'collection_trend',
                'expense_trend',
                'revenue_by_fee_type'
            )
        );
    }

    private function getMonthlyCollectionTrend($year)
    {
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[] = \App\Models\Finance\Payment::whereYear('payment_date', $year)
                ->whereMonth('payment_date', $m)
                ->sum('amount');
        }
        return $months;
    }

    private function getMonthlyExpenseTrend($year)
    {
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[] = \App\Models\Finance\Expense::where('status', 'approved')
                ->whereYear('expense_date', $year)
                ->whereMonth('expense_date', $m)
                ->sum('amount');
        }
        return $months;
    }

    private function getRevenueByFeeType($year)
    {
        return \App\Models\Finance\InvoiceItem::join(
            'finance_fee_types',
            'finance_invoice_items.fee_type_id',
            '=',
            'finance_fee_types.id'
        )
            ->join('finance_invoices', 'finance_invoice_items.invoice_id', '=', 'finance_invoices.id')
            ->whereYear('finance_invoices.issue_date', $year)
            ->groupBy('finance_fee_types.id', 'finance_fee_types.name')
            ->selectRaw('finance_fee_types.name, sum(finance_invoice_items.amount) as total')
            ->get();
    }
}
