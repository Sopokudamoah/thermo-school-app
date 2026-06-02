<?php

namespace App\Http\Controllers\Finance;

use App\DataTables\Finance\ScholarshipDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\ScholarshipRequest;
use App\Interfaces\Finance\AuditLogInterface;
use App\Interfaces\Finance\InvoiceInterface;
use App\Interfaces\Finance\ScholarshipInterface;
use App\Models\Finance\Scholarship;
use App\Models\Finance\StudentScholarship;
use App\Models\Student;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    protected $scholarshipRepository;
    protected $invoiceRepository;
    protected $auditLogRepository;

    public function __construct(
        ScholarshipInterface $scholarshipRepository,
        InvoiceInterface $invoiceRepository,
        AuditLogInterface $auditLogRepository
    ) {
        $this->middleware(['can:finance.view']);
        $this->scholarshipRepository = $scholarshipRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->auditLogRepository = $auditLogRepository;
    }

    public function index(ScholarshipDataTable $dataTable)
    {
        return $dataTable->render('finance.scholarships.index');
    }

    public function create()
    {
        return view('finance.scholarships.create');
    }

    public function store(ScholarshipRequest $request)
    {
        try {
            $this->scholarshipRepository->create($request->validated());
            return redirect()->route('finance.scholarships.index')->with('status', 'Scholarship created successfully.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function edit($id)
    {
        $scholarship = $this->scholarshipRepository->findById($id);
        return view('finance.scholarships.edit', compact('scholarship'));
    }

    public function update(ScholarshipRequest $request, $id)
    {
        try {
            $this->scholarshipRepository->update($id, $request->validated());
            return redirect()->route('finance.scholarships.index')->with('status', 'Scholarship updated successfully.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function assignCreate(Request $request)
    {
        $scholarships = $this->scholarshipRepository->getActive();
        $students = Student::orderBy('first_name')->get();
        $student_id = $request->get('student_id');
        $invoices = $student_id ? $this->invoiceRepository->getByStudent($student_id) : collect();

        return view('finance.scholarships.assign', compact('scholarships', 'students', 'student_id', 'invoices'));
    }

    public function assignStore(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'scholarship_id' => 'required|integer|exists:finance_scholarships,id',
            'invoice_id' => 'nullable|integer|exists:finance_invoices,id',
            'approval_date' => 'required|date',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'notes' => 'nullable|string',
        ]);

        try {
            $data = $request->all();
            $data['approved_by'] = auth()->id();
            $data['status'] = 'active';

            $ss = $this->scholarshipRepository->assignToStudent($data);

            if ($ss->invoice_id) {
                $this->invoiceRepository->recalculateTotals($ss->invoice_id);
            }

            $this->auditLogRepository->log(
                auth()->id(),
                'scholarship_approved',
                StudentScholarship::class,
                $ss->id,
                null,
                $ss->toArray()
            );

            return redirect()->route('finance.scholarships.index')->with(
                'status',
                'Scholarship assigned successfully.'
            );
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
