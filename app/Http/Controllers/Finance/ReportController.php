<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Interfaces\Finance\ExpenseInterface;
use App\Interfaces\Finance\InvoiceInterface;
use App\Interfaces\Finance\PaymentInterface;
use App\Models\Finance\Expense;
use App\Models\Finance\ExpenseCategory;
use App\Models\Finance\Invoice;
use App\Models\Finance\InvoiceItem;
use App\Models\Finance\Payment;
use App\Models\SchoolClass;
use App\Models\SchoolSession;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $invoiceRepository;
    protected $paymentRepository;
    protected $expenseRepository;

    public function __construct(
        InvoiceInterface $invoiceRepository,
        PaymentInterface $paymentRepository,
        ExpenseInterface $expenseRepository
    ) {
        $this->middleware(['can:finance.report.view']);
        $this->invoiceRepository = $invoiceRepository;
        $this->paymentRepository = $paymentRepository;
        $this->expenseRepository = $expenseRepository;
    }

    public function index()
    {
        return view('finance.reports.index');
    }

    public function feeCollection(Request $request)
    {
        $filters = $request->only(['from_date', 'to_date', 'class_id', 'section_id']);
        $sessions = SchoolSession::orderByDesc('id')->get();
        $classes = SchoolClass::orderBy('class_name')->get();
        $sections = Section::orderBy('section_name')->get();

        $query = Payment::with(['student', 'allocations.invoice'])
            ->when(!empty($filters['from_date']), fn($q) => $q->whereDate('payment_date', '>=', $filters['from_date']))
            ->when(!empty($filters['to_date']), fn($q) => $q->whereDate('payment_date', '<=', $filters['to_date']));

        $payments = $query->orderByDesc('payment_date')->get();
        $total = $payments->sum('amount');

        return view(
            'finance.reports.fee-collection',
            compact('payments', 'total', 'filters', 'sessions', 'classes', 'sections')
        );
    }

    public function outstanding(Request $request)
    {
        $bucket = $request->get('bucket');
        $aging = $this->invoiceRepository->getAgingReport();

        $buckets_display = [
            '0_30' => '0–30 days',
            '31_60' => '31–60 days',
            '61_90' => '61–90 days',
            '90_plus' => '90+ days',
        ];

        return view('finance.reports.outstanding', compact('aging', 'bucket', 'buckets_display'));
    }

    public function revenue(Request $request)
    {
        $from = $request->get('from_date', date('Y') . '-01-01');
        $to = $request->get('to_date', date('Y') . '-12-31');

        $revenue = InvoiceItem::join(
            'finance_fee_types',
            'finance_invoice_items.fee_type_id',
            '=',
            'finance_fee_types.id'
        )
            ->join('finance_invoices', 'finance_invoice_items.invoice_id', '=', 'finance_invoices.id')
            ->whereDate('finance_invoices.issue_date', '>=', $from)
            ->whereDate('finance_invoices.issue_date', '<=', $to)
            ->groupBy('finance_fee_types.id', 'finance_fee_types.name')
            ->selectRaw('finance_fee_types.name as fee_type, sum(finance_invoice_items.amount) as total')
            ->get();

        return view('finance.reports.revenue', compact('revenue', 'from', 'to'));
    }

    public function expenseReport(Request $request)
    {
        $filters = $request->only(['from_date', 'to_date']);
        $categories = ExpenseCategory::withCount('expenses')->get();

        $expense_data = Expense::with('category')
            ->where('status', Expense::STATUS_APPROVED)
            ->when(!empty($filters['from_date']), fn($q) => $q->whereDate('expense_date', '>=', $filters['from_date']))
            ->when(!empty($filters['to_date']), fn($q) => $q->whereDate('expense_date', '<=', $filters['to_date']))
            ->groupBy('category_id')
            ->selectRaw('category_id, sum(amount) as total, count(*) as count')
            ->with('category')
            ->get();

        return view('finance.reports.expense', compact('expense_data', 'filters', 'categories'));
    }

    public function studentLedger(Request $request)
    {
        $student_id = $request->get('student_id');
        $student = $student_id ? Student::findOrFail($student_id) : null;

        $invoices = $student_id
            ? $this->invoiceRepository->getByStudent($student_id)
            : collect();

        $payments = $student_id
            ? $this->paymentRepository->getByStudent($student_id)
            : collect();

        $students = Student::orderBy('first_name')->get();

        return view('finance.reports.student-ledger', compact('student', 'invoices', 'payments', 'students'));
    }

    public function auditTrail(Request $request)
    {
        $filters = $request->only(['action', 'user_id', 'from_date', 'to_date']);
        $logs = \App\Models\Finance\AuditLog::with('user')
            ->when(!empty($filters['action']), fn($q) => $q->where('action', $filters['action']))
            ->when(!empty($filters['user_id']), fn($q) => $q->where('user_id', $filters['user_id']))
            ->when(!empty($filters['from_date']), fn($q) => $q->whereDate('created_at', '>=', $filters['from_date']))
            ->when(!empty($filters['to_date']), fn($q) => $q->whereDate('created_at', '<=', $filters['to_date']))
            ->orderByDesc('created_at')
            ->paginate(50);

        return view('finance.reports.audit-trail', compact('logs', 'filters'));
    }
}
