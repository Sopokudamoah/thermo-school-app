<?php

namespace App\Http\Controllers\Finance;

use App\DataTables\Finance\InvoiceDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\InvoiceRequest;
use App\Interfaces\Finance\AuditLogInterface;
use App\Interfaces\Finance\DiscountInterface;
use App\Interfaces\Finance\FeeStructureInterface;
use App\Interfaces\Finance\FeeTypeInterface;
use App\Interfaces\Finance\InvoiceInterface;
use App\Interfaces\Finance\ScholarshipInterface;
use App\Models\Finance\Invoice;
use App\Models\Finance\InvoiceItem;
use App\Models\SchoolSession;
use App\Models\Semester;
use App\Models\Student;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    protected $invoiceRepository;
    protected $feeTypeRepository;
    protected $feeStructureRepository;
    protected $discountRepository;
    protected $scholarshipRepository;
    protected $auditLogRepository;

    public function __construct(
        InvoiceInterface $invoiceRepository,
        FeeTypeInterface $feeTypeRepository,
        FeeStructureInterface $feeStructureRepository,
        DiscountInterface $discountRepository,
        ScholarshipInterface $scholarshipRepository,
        AuditLogInterface $auditLogRepository
    ) {
        $this->middleware(['can:finance.view']);
        $this->invoiceRepository = $invoiceRepository;
        $this->feeTypeRepository = $feeTypeRepository;
        $this->feeStructureRepository = $feeStructureRepository;
        $this->discountRepository = $discountRepository;
        $this->scholarshipRepository = $scholarshipRepository;
        $this->auditLogRepository = $auditLogRepository;
    }

    public function index(InvoiceDataTable $dataTable)
    {
        return $dataTable->render('finance.invoices.index');
    }

    public function create()
    {
        $students = Student::orderBy('first_name')->get();
        $sessions = SchoolSession::orderByDesc('id')->get();
        $semesters = Semester::orderBy('semester_name')->get();
        $fee_types = $this->feeTypeRepository->getActive();
        $discounts = $this->discountRepository->getActive();

        return view('finance.invoices.create', compact('students', 'sessions', 'semesters', 'fee_types', 'discounts'));
    }

    public function store(InvoiceRequest $request)
    {
        try {
            $data = $request->validated();
            $items = $data['items'];
            unset($data['items']);

            $data['invoice_number'] = $this->invoiceRepository->generateInvoiceNumber();
            $data['created_by'] = auth()->id();
            $data['status'] = Invoice::STATUS_PENDING;

            $invoice = $this->invoiceRepository->create($data);

            foreach ($items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'fee_type_id' => $item['fee_type_id'],
                    'description' => $item['description'],
                    'amount' => $item['amount'],
                ]);
            }

            $this->invoiceRepository->recalculateTotals($invoice->id);

            $this->auditLogRepository->log(
                auth()->id(),
                'invoice_created',
                Invoice::class,
                $invoice->id,
                null,
                $invoice->toArray()
            );

            return redirect()->route('finance.invoices.show', $invoice->id)->with(
                'status',
                'Invoice created successfully.'
            );
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function show($id)
    {
        $invoice = $this->invoiceRepository->findById($id);
        $discounts = $this->discountRepository->getActive();
        $scholarships = $this->scholarshipRepository->getActive();
        return view('finance.invoices.show', compact('invoice', 'discounts', 'scholarships'));
    }

    public function edit($id)
    {
        $invoice = $this->invoiceRepository->findById($id);

        if (!in_array($invoice->status, [Invoice::STATUS_DRAFT, Invoice::STATUS_PENDING])) {
            return back()->withError('Only draft or pending invoices can be edited.');
        }

        $students = Student::orderBy('first_name')->get();
        $sessions = SchoolSession::orderByDesc('id')->get();
        $semesters = Semester::orderBy('semester_name')->get();
        $fee_types = $this->feeTypeRepository->getActive();

        return view('finance.invoices.edit', compact('invoice', 'students', 'sessions', 'semesters', 'fee_types'));
    }

    public function update(InvoiceRequest $request, $id)
    {
        try {
            $invoice = $this->invoiceRepository->findById($id);
            $old_values = $invoice->toArray();

            $data = $request->validated();
            $items = $data['items'];
            unset($data['items']);

            $this->invoiceRepository->update($id, $data);

            \App\Models\Finance\InvoiceItem::where('invoice_id', $id)->delete();
            foreach ($items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $id,
                    'fee_type_id' => $item['fee_type_id'],
                    'description' => $item['description'],
                    'amount' => $item['amount'],
                ]);
            }

            $updated = $this->invoiceRepository->recalculateTotals($id);

            $this->auditLogRepository->log(
                auth()->id(),
                'invoice_updated',
                Invoice::class,
                $id,
                $old_values,
                $updated->toArray()
            );

            return redirect()->route('finance.invoices.show', $id)->with('status', 'Invoice updated successfully.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function cancel($id)
    {
        try {
            $invoice = $this->invoiceRepository->findById($id);
            $old_values = $invoice->toArray();
            $this->invoiceRepository->updateStatus($id, Invoice::STATUS_CANCELLED);

            $this->auditLogRepository->log(
                auth()->id(),
                'invoice_cancelled',
                Invoice::class,
                $id,
                $old_values,
                ['status' => Invoice::STATUS_CANCELLED]
            );

            return back()->with('status', 'Invoice cancelled.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function applyDiscount(Request $request, $id)
    {
        $request->validate(['discount_id' => 'required|integer|exists:finance_discounts,id']);
        try {
            $this->discountRepository->applyToInvoice($id, $request->discount_id);
            $this->invoiceRepository->recalculateTotals($id);
            return back()->with('status', 'Discount applied.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function removeDiscount(Request $request, $id)
    {
        $request->validate(['discount_id' => 'required|integer']);
        try {
            $this->discountRepository->removeFromInvoice($id, $request->discount_id);
            $this->invoiceRepository->recalculateTotals($id);
            return back()->with('status', 'Discount removed.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function print($id)
    {
        $invoice = $this->invoiceRepository->findById($id);
        return view('finance.invoices.print', compact('invoice'));
    }
}
