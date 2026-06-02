<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class FinancePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'finance.view',
            'finance.fee-type.create',
            'finance.fee-type.edit',
            'finance.fee-structure.create',
            'finance.fee-structure.edit',
            'finance.invoice.create',
            'finance.invoice.edit',
            'finance.invoice.view',
            'finance.payment.create',
            'finance.payment.view',
            'finance.discount.create',
            'finance.discount.edit',
            'finance.scholarship.create',
            'finance.scholarship.edit',
            'finance.scholarship.approve',
            'finance.expense.create',
            'finance.expense.edit',
            'finance.expense.approve',
            'finance.vendor.create',
            'finance.vendor.edit',
            'finance.budget.manage',
            'finance.report.view',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $admin = \App\Models\User::where('email', 'admin@ut.com')->first();
        if ($admin) {
            $admin->givePermissionTo($permissions);
        }
    }
}
