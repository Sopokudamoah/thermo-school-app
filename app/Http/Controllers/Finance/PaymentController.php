<?php

namespace App\Http\Controllers\Finance;

use App\DataTables\Finance\PaymentDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\PaymentRequest;
use App\Interfaces\Finance\AuditLogInterface;
use App\Interfaces\Finance\InvoiceInterface;
use App\Interfaces\Finance\PaymentInterface;
use App\Models\Finance\Payment;
use App\Models\Student;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentRepository;
    protected $invoiceRepository;
    protected $auditLogRepository;

    public function __construct(
        PaymentInterface $paymentRepository,
        InvoiceInterface $invoiceRepository,
        AuditLogInterface $auditLogRepository
    ) {
        $this->middleware(['can:finance.view']);
        $this->paymentRepository = $paymentRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->auditLogRepository = $auditLogRepository;
    }

    public function index(PaymentDataTable $dataTable)
    {
        return $dataTable->render('finance.payments.index');
    }

    public function create(Request $request)
    {
        $student_id = $request->get('student_id');
        $student = $student_id ? Student::find($student_id) : null;
        $outstanding_invoices = $student_id
            ? $this->invoiceRepository->getOutstandingByStudent($student_id)
            : collect();

        $students = Student::orderBy('first_name')->get();
        $methods = [
            Payment::METHOD_CASH => 'Cash',
            Payment::METHOD_BANK_TRANSFER => 'Bank Transfer',
            Payment::METHOD_CARD => 'Card',
            Payment::METHOD_ONLINE_GATEWAY => 'Online Gateway',
            Payment::METHOD_MOBILE_MONEY => 'Mobile Money',
        ];

        return view('finance.payments.create', compact('student', 'outstanding_invoices', 'students', 'methods'));
    }

    public function store(PaymentRequest $request)
    {
        try {
            $data = $request->validated();
            $allocations = $data['allocations'];
            unset($data['allocations']);

            $data['receipt_number'] = $this->paymentRepository->generateReceiptNumber();
            $data['received_by'] = auth()->id();

            $payment = $this->paymentRepository->create($data, $allocations);

            foreach ($allocations as $allocation) {
                $this->invoiceRepository->recalculateTotals($allocation['invoice_id']);
            }

            $this->auditLogRepository->log(
                auth()->id(),
                'payment_posted',
                Payment::class,
                $payment->id,
                null,
                $payment->toArray()
            );

            return redirect()->route('finance.payments.show', $payment->id)->with(
                'status',
                'Payment recorded successfully.'
            );
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function show($id)
    {
        $payment = $this->paymentRepository->findById($id);
        return view('finance.payments.show', compact('payment'));
    }

    public function receipt($id)
    {
        $payment = $this->paymentRepository->findById($id);
        return view('finance.payments.receipt', compact('payment'));
    }

    public function getStudentInvoices(Request $request)
    {
        $request->validate(['student_id' => 'required|integer']);
        $invoices = $this->invoiceRepository->getOutstandingByStudent($request->student_id);
        return response()->json($invoices);
    }
}
