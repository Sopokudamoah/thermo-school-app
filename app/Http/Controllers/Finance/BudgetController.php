<?php

namespace App\Http\Controllers\Finance;

use App\DataTables\Finance\BudgetDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\BudgetRequest;
use App\Interfaces\Finance\BudgetInterface;
use App\Models\Finance\BudgetCategory;
use App\Models\Finance\BudgetDepartment;
use App\Models\Finance\ExpenseCategory;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    protected $budgetRepository;

    public function __construct(BudgetInterface $budgetRepository)
    {
        $this->middleware(['can:finance.budget.manage']);
        $this->budgetRepository = $budgetRepository;
    }

    public function index(BudgetDataTable $dataTable)
    {
        return $dataTable->render('finance.budgets.index');
    }

    public function create()
    {
        $expense_categories = ExpenseCategory::where('active', true)->get();
        return view('finance.budgets.create', compact('expense_categories'));
    }

    public function store(BudgetRequest $request)
    {
        try {
            $budget = $this->budgetRepository->create($request->validated());
            return redirect()->route('finance.budgets.show', $budget->id)->with(
                'status',
                'Budget created successfully.'
            );
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function show($id)
    {
        $budget = $this->budgetRepository->findById($id);
        $variance = $this->budgetRepository->getVarianceReport($id);
        return view('finance.budgets.show', compact('budget', 'variance'));
    }

    public function edit($id)
    {
        $budget = $this->budgetRepository->findById($id);
        $expense_categories = ExpenseCategory::where('active', true)->get();
        return view('finance.budgets.edit', compact('budget', 'expense_categories'));
    }

    public function update(BudgetRequest $request, $id)
    {
        try {
            $this->budgetRepository->update($id, $request->validated());
            return redirect()->route('finance.budgets.show', $id)->with('status', 'Budget updated successfully.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function addDepartment(Request $request, $budget_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'allocated' => 'required|numeric|min:0',
        ]);

        try {
            BudgetDepartment::create([
                'budget_id' => $budget_id,
                'name' => $request->name,
                'allocated' => $request->allocated,
            ]);
            return back()->with('status', 'Department added.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function addCategory(Request $request, $dept_id)
    {
        $request->validate([
            'expense_category_id' => 'required|integer|exists:finance_expense_categories,id',
            'allocated' => 'required|numeric|min:0',
        ]);

        try {
            BudgetCategory::create([
                'budget_department_id' => $dept_id,
                'expense_category_id' => $request->expense_category_id,
                'allocated' => $request->allocated,
            ]);
            return back()->with('status', 'Category added.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
