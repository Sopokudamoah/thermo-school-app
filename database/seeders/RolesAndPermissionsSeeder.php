<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Ensure permissions are created (calling existing seeders or defining here)
        $this->call([
            PermissionSeeder::class,
            FinancePermissionSeeder::class,
        ]);

        // Create Roles
        $adminRole = Role::findOrCreate('Administrator');
        $teacherRole = Role::findOrCreate('Teacher');
        $financeRole = Role::findOrCreate('Finance');

        // Assign Permissions to Administrator
        $adminRole->givePermissionTo(Permission::all());

        // Assign Permissions to Teacher
        $teacherPermissions = [
            'view syllabi',
            'view routines',
            'view exams',
            'view exams rule',
            'view exams history',
            'view grading systems',
            'take attendances',
            'view attendances',
            'submit assignments',
            'create assignments',
            'view assignments',
            'save marks',
            'view marks',
            'view courses',
            'view classes',
            'view sections'
        ];
        foreach ($teacherPermissions as $perm) {
            if (Permission::where('name', $perm)->exists()) {
                $teacherRole->givePermissionTo($perm);
            }
        }

        // Assign Permissions to Finance
        $financePermissions = [
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
        foreach ($financePermissions as $perm) {
            if (Permission::where('name', $perm)->exists()) {
                $financeRole->givePermissionTo($perm);
            }
        }

        // Migrate users from 'role' column to Spatie roles
        User::all()->each(function ($user) use ($adminRole, $teacherRole, $financeRole) {
            if ($user->role === 'admin') {
                $user->assignRole($adminRole);
            } elseif ($user->role === 'teacher') {
                $user->assignRole($teacherRole);
            } elseif ($user->role === 'finance') {
                $user->assignRole($financeRole);
            }
            // For students, we might not need a Spatie role yet based on the request,
            // but we can add one if needed. The request specifically asked for Administrator, Teacher, and Finance.
        });
    }
}
