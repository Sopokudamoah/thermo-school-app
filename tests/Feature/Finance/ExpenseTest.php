<?php

namespace Tests\Feature\Finance;

use App\Models\User;
use App\Models\Finance\Expense;
use App\Models\Finance\ExpenseCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        Permission::create(['name' => 'finance.view']);
        Permission::create(['name' => 'finance.expense.create']);
        Permission::create(['name' => 'finance.expense.approve']);
        $this->admin->givePermissionTo(['finance.view', 'finance.expense.create', 'finance.expense.approve']);

        ExpenseCategory::create(['id' => 1, 'name' => 'Salary', 'code' => 'SAL', 'active' => true]);
    }

    public function test_admin_can_view_expenses()
    {
        $response = $this->actingAs($this->admin)->get(route('finance.expenses.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_submit_expense()
    {
        $data = [
            'category_id' => 1,
            'amount' => 1000,
            'expense_date' => date('Y-m-d'),
            'description' => 'Staff salaries'
        ];

        $response = $this->actingAs($this->admin)->post(route('finance.expenses.store'), $data);

        $response->assertStatus(302);
        $this->assertDatabaseHas('finance_expenses', [
            'category_id' => 1,
            'amount' => 1000,
            'status' => 'pending'
        ]);
    }

    public function test_admin_can_approve_expense()
    {
        $expense = Expense::create([
            'category_id' => 1,
            'amount' => 500,
            'expense_date' => date('Y-m-d'),
            'description' => 'Utilities',
            'submitted_by' => $this->admin->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->admin)->post(route('finance.expenses.approve', $expense->id));

        $response->assertStatus(302);
        $this->assertDatabaseHas('finance_expenses', [
            'id' => $expense->id,
            'status' => 'approved',
            'approved_by' => $this->admin->id
        ]);
    }
}
