<?php

namespace App\Http\Controllers\Finance;

use App\DataTables\Finance\ExpenseDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\ExpenseRequest;
use App\Interfaces\Finance\AuditLogInterface;
use App\Interfaces\Finance\ExpenseInterface;
use App\Interfaces\Finance\VendorInterface;
use App\Models\Finance\Expense;
use App\Models\Finance\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    protected $expenseRepository;
    protected $vendorRepository;
    protected $auditLogRepository;

    public function __construct(
        ExpenseInterface $expenseRepository,
        VendorInterface $vendorRepository,
        AuditLogInterface $auditLogRepository
    ) {
        $this->middleware(['can:finance.view']);
        $this->expenseRepository = $expenseRepository;
        $this->vendorRepository = $vendorRepository;
        $this->auditLogRepository = $auditLogRepository;
    }

    public function index(ExpenseDataTable $dataTable)
    {
        $categories = ExpenseCategory::where('active', true)->get();
        return $dataTable->render('finance.expenses.index', compact('categories'));
    }

    public function create()
    {
        $categories = ExpenseCategory::where('active', true)->get();
        $vendors = $this->vendorRepository->getActive();
        return view('finance.expenses.create', compact('categories', 'vendors'));
    }

    public function store(ExpenseRequest $request)
    {
        try {
            $data = $request->validated();
            $data['submitted_by'] = auth()->id();
            $data['status'] = Expense::STATUS_PENDING;

            if ($request->hasFile('receipt')) {
                $data['receipt_path'] = $request->file('receipt')->store('finance/receipts', 'public');
            }
            unset($data['receipt']);

            $expense = $this->expenseRepository->create($data);

            return redirect()->route('finance.expenses.index')->with('status', 'Expense submitted successfully.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function show($id)
    {
        $expense = $this->expenseRepository->findById($id);
        return view('finance.expenses.show', compact('expense'));
    }

    public function edit($id)
    {
        $expense = $this->expenseRepository->findById($id);

        if ($expense->status !== Expense::STATUS_PENDING) {
            return back()->withError('Only pending expenses can be edited.');
        }

        $categories = ExpenseCategory::where('active', true)->get();
        $vendors = $this->vendorRepository->getActive();
        return view('finance.expenses.edit', compact('expense', 'categories', 'vendors'));
    }

    public function update(ExpenseRequest $request, $id)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('receipt')) {
                $existing = $this->expenseRepository->findById($id);
                if ($existing->receipt_path) {
                    Storage::disk('public')->delete($existing->receipt_path);
                }
                $data['receipt_path'] = $request->file('receipt')->store('finance/receipts', 'public');
            }
            unset($data['receipt']);

            $this->expenseRepository->update($id, $data);
            return redirect()->route('finance.expenses.index')->with('status', 'Expense updated successfully.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function approve($id)
    {
        $this->middleware(['can:finance.expense.approve']);
        try {
            $expense = $this->expenseRepository->findById($id);
            $old_values = $expense->toArray();

            $updated = $this->expenseRepository->approve($id, auth()->id());

            $this->auditLogRepository->log(
                auth()->id(),
                'expense_approved',
                Expense::class,
                $id,
                $old_values,
                $updated->toArray()
            );

            return back()->with('status', 'Expense approved.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string']);
        $this->middleware(['can:finance.expense.approve']);
        try {
            $expense = $this->expenseRepository->findById($id);
            $old_values = $expense->toArray();

            $updated = $this->expenseRepository->reject($id, $request->reason);

            $this->auditLogRepository->log(
                auth()->id(),
                'expense_rejected',
                Expense::class,
                $id,
                $old_values,
                $updated->toArray()
            );

            return back()->with('status', 'Expense rejected.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
