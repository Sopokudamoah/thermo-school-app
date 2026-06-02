<?php

namespace Tests\Feature\Finance;

use App\Models\User;
use App\Models\Student;
use App\Models\Finance\Invoice;
use App\Models\Finance\FeeType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        Permission::create(['name' => 'finance.view']);
        Permission::create(['name' => 'finance.invoice.create']);
        Permission::create(['name' => 'finance.invoice.view']);
        $this->admin->givePermissionTo(['finance.view', 'finance.invoice.create', 'finance.invoice.view']);

        Student::factory()->create(['id' => 1]);
        FeeType::create(['id' => 1, 'name' => 'Tuition', 'code' => 'TUI', 'active' => true]);
    }

    public function test_admin_can_view_invoices()
    {
        $response = $this->actingAs($this->admin)->get(route('finance.invoices.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_invoice()
    {
        $data = [
            'student_id' => 1,
            'issue_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d', strtotime('+30 days')),
            'items' => [
                ['fee_type_id' => 1, 'amount' => 500]
            ]
        ];

        $response = $this->actingAs($this->admin)->post(route('finance.invoices.store'), $data);

        $response->assertStatus(302);
        $this->assertDatabaseHas('finance_invoices', [
            'student_id' => 1,
            'total' => 500
        ]);
    }
}
