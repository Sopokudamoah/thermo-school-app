<?php

namespace Database\Seeders;

use App\Models\Finance\Budget;
use App\Models\Finance\BudgetCategory;
use App\Models\Finance\BudgetDepartment;
use App\Models\Finance\Discount;
use App\Models\Finance\Expense;
use App\Models\Finance\ExpenseCategory;
use App\Models\Finance\FeeStructure;
use App\Models\Finance\FeeStructureItem;
use App\Models\Finance\FeeType;
use App\Models\Finance\Invoice;
use App\Models\Finance\InvoiceItem;
use App\Models\Finance\Payment;
use App\Models\Finance\PaymentAllocation;
use App\Models\Finance\Scholarship;
use App\Models\Finance\Vendor;
use App\Models\SchoolClass;
use App\Models\SchoolSession;
use App\Models\Semester;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FinanceDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Fee Types
        $feeTypes = [
            ['name' => 'Tuition Fee', 'code' => 'TUI', 'recurring' => true, 'active' => true],
            ['name' => 'Admission Fee', 'code' => 'ADM', 'recurring' => false, 'active' => true],
            ['name' => 'Library Fee', 'code' => 'LIB', 'recurring' => true, 'active' => true],
            ['name' => 'Laboratory Fee', 'code' => 'LAB', 'recurring' => true, 'active' => true],
            ['name' => 'Sports Fee', 'code' => 'SPT', 'recurring' => true, 'active' => true],
            ['name' => 'Examination Fee', 'code' => 'EXM', 'recurring' => true, 'active' => true],
        ];

        foreach ($feeTypes as $ft) {
            FeeType::updateOrCreate(['code' => $ft['code']], $ft);
        }

        $session = SchoolSession::first();
        $semester = Semester::first();
        $classes = SchoolClass::limit(3)->get();

        if ($session && $semester && $classes->count() > 0) {
            // 2. Fee Structures
            foreach ($classes as $class) {
                $fs = FeeStructure::updateOrCreate([
                    'name' => 'Standard Fee Structure - ' . $class->class_name,
                    'session_id' => $session->id,
                    'semester_id' => $semester->id,
                    'class_id' => $class->id,
                ], [
                    'active' => true,
                ]);

                $types = FeeType::all();
                foreach ($types as $type) {
                    FeeStructureItem::updateOrCreate([
                        'fee_structure_id' => $fs->id,
                        'fee_type_id' => $type->id,
                    ], [
                        'amount' => rand(50, 500),
                    ]);
                }
            }
        }

        // 3. Vendors
        $vendors = [
            [
                'name' => 'Global Stationeries',
                'contact_person' => 'John Doe',
                'email' => 'john@global.com',
                'phone' => '123456789',
                'active' => true
            ],
            [
                'name' => 'City Maintenance Services',
                'contact_person' => 'Jane Smith',
                'email' => 'jane@citymaint.com',
                'phone' => '987654321',
                'active' => true
            ],
            [
                'name' => 'EduBooks Publishing',
                'contact_person' => 'Mark Book',
                'email' => 'mark@edubooks.com',
                'phone' => '456789123',
                'active' => true
            ],
        ];

        foreach ($vendors as $v) {
            Vendor::updateOrCreate(['name' => $v['name']], $v);
        }

        // 4. Expense Categories
        $expCategories = [
            ['name' => 'Academic Supplies', 'description' => 'Books, chalks, etc.', 'active' => true],
            ['name' => 'Maintenance', 'description' => 'Repairs and cleaning.', 'active' => true],
            ['name' => 'Utilities', 'description' => 'Electricity, water.', 'active' => true],
            ['name' => 'Salaries', 'description' => 'Staff payments.', 'active' => true],
        ];

        foreach ($expCategories as $ec) {
            ExpenseCategory::updateOrCreate(['name' => $ec['name']], $ec);
        }

        // 5. Expenses
        $vendor = Vendor::first();
        $category = ExpenseCategory::first();
        $admin = \App\Models\User::first();

        if ($vendor && $category && $admin) {
            $expenses = [
                [
                    'title' => 'Office Supplies Purchase',
                    'category_id' => $category->id,
                    'vendor_id' => $vendor->id,
                    'amount' => 1200.50,
                    'expense_date' => Carbon::now()->subDays(10),
                    'status' => Expense::STATUS_APPROVED,
                    'submitted_by' => $admin->id,
                    'approved_by' => $admin->id,
                    'approved_at' => Carbon::now()->subDays(9),
                ],
                [
                    'title' => 'A/C Repair',
                    'category_id' => ExpenseCategory::where('name', 'Maintenance')->first()?->id ?? $category->id,
                    'vendor_id' => Vendor::skip(1)->first()?->id ?? $vendor->id,
                    'amount' => 450.00,
                    'expense_date' => Carbon::now()->subDays(5),
                    'status' => Expense::STATUS_PENDING,
                    'submitted_by' => $admin->id,
                ]
            ];

            foreach ($expenses as $e) {
                Expense::create($e);
            }
        }

        // 6. Scholarships & Discounts
        $scholarships = [
            [
                'name' => 'Merit Scholarship',
                'type' => Scholarship::TYPE_PERCENTAGE,
                'value' => 50,
                'description' => 'For top students',
                'active' => true
            ],
            [
                'name' => 'Sport Scholarship',
                'type' => Scholarship::TYPE_FIXED,
                'value' => 100,
                'description' => 'For athletes',
                'active' => true
            ],
        ];

        foreach ($scholarships as $s) {
            Scholarship::updateOrCreate(['name' => $s['name']], $s);
        }

        $discounts = [
            [
                'name' => 'Early Bird Discount',
                'code' => 'EARLY10',
                'type' => Discount::TYPE_PERCENTAGE,
                'value' => 10,
                'active' => true
            ],
            [
                'name' => 'Sibling Discount',
                'code' => 'SIB20',
                'type' => Discount::TYPE_FIXED,
                'value' => 20,
                'active' => true
            ],
        ];

        foreach ($discounts as $d) {
            Discount::updateOrCreate(['code' => $d['code']], $d);
        }

        // 7. Budgeting
        $budget = Budget::updateOrCreate([
            'name' => 'Annual School Budget ' . Carbon::now()->year,
            'year' => Carbon::now()->year,
        ], [
            'total_allocated' => 100000,
            'active' => true,
        ]);

        $dept = BudgetDepartment::updateOrCreate([
            'budget_id' => $budget->id,
            'name' => 'Academic Affairs',
        ], [
            'allocated' => 50000,
        ]);

        if ($category) {
            BudgetCategory::updateOrCreate([
                'budget_department_id' => $dept->id,
                'expense_category_id' => $category->id,
            ], [
                'allocated' => 20000,
            ]);
        }

        // 8. Invoices and Payments for students
        $students = Student::limit(10)->get();
        if ($students->count() > 0 && $session && $semester) {
            foreach ($students as $index => $student) {
                $subtotal = rand(1000, 2000);
                $invoice = Invoice::create([
                    'invoice_number' => 'INV-' . Carbon::now()->year . '-' . str_pad(
                            $student->id,
                            4,
                            '0',
                            STR_PAD_LEFT
                        ) . '-' . $index,
                    'student_id' => $student->id,
                    'session_id' => $session->id,
                    'semester_id' => $semester->id,
                    'issue_date' => Carbon::now()->subMonths(1),
                    'due_date' => Carbon::now()->addDays(15),
                    'subtotal' => $subtotal,
                    'total' => $subtotal,
                    'paid_amount' => 0,
                    'balance' => $subtotal,
                    'status' => Invoice::STATUS_PENDING,
                    'created_by' => $admin->id,
                ]);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'fee_type_id' => FeeType::inRandomOrder()->first()->id,
                    'amount' => $subtotal,
                    'description' => 'Tuition Fee for Semester',
                ]);

                // Create a payment for some students
                if ($index % 2 == 0) {
                    $paidAmount = ($index % 4 == 0) ? $subtotal : $subtotal / 2;
                    $payment = Payment::create([
                        'receipt_number' => 'RCP-' . time() . $index,
                        'student_id' => $student->id,
                        'payment_date' => Carbon::now(),
                        'amount' => $paidAmount,
                        'method' => Payment::METHOD_CASH,
                        'received_by' => $admin->id,
                    ]);

                    PaymentAllocation::create([
                        'payment_id' => $payment->id,
                        'invoice_id' => $invoice->id,
                        'amount' => $paidAmount,
                    ]);

                    $invoice->update([
                        'paid_amount' => $paidAmount,
                        'balance' => $subtotal - $paidAmount,
                        'status' => ($paidAmount == $subtotal) ? Invoice::STATUS_PAID : Invoice::STATUS_PARTIALLY_PAID,
                    ]);
                }
            }
        }
    }
}
