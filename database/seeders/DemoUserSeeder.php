<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Create Teachers
        $teachers = User::factory()->count(10)->create(['role' => 'teacher']);
        foreach ($teachers as $teacher) {
            $teacher->givePermissionTo([
                'view courses',
                'view classes',
                'view sections',
                'view routines',
                'take attendances',
                'view attendances',
                'save marks',
                'view marks',
                'create assignments',
                'view assignments',
                'view notices',
                'view events',
                'view syllabi',
            ]);
        }

        // 2. Create Students
        $students = User::factory()->count(50)->create(['role' => 'student']);
        foreach ($students as $student) {
            $student->givePermissionTo([
                'view courses',
                'view routines',
                'view attendances',
                'view marks',
                'submit assignments',
                'view assignments',
                'view notices',
                'view events',
                'view syllabi',
            ]);
        }

        // 3. Create Parents
        $parents = User::factory()->count(20)->create(['role' => 'parent']);
        foreach ($parents as $parent) {
            $parent->givePermissionTo([
                'view attendances',
                'view marks',
                'view notices',
                'view events',
            ]);
        }

        // Ensure there is at least one known teacher and student for demo login
        if (!User::where('email', 'teacher@ut.com')->exists()) {
            User::factory()->create([
                'first_name' => 'John',
                'last_name' => 'Teacher',
                'email' => 'teacher@ut.com',
                'role' => 'teacher',
            ])->givePermissionTo([
                'view courses', 'view classes', 'view sections', 'view routines', 'take attendances', 'view attendances', 'save marks', 'view marks', 'create assignments', 'view assignments', 'view notices', 'view events', 'view syllabi'
            ]);
        }

        if (!User::where('email', 'student@ut.com')->exists()) {
            User::factory()->create([
                'first_name' => 'Jane',
                'last_name' => 'Student',
                'email' => 'student@ut.com',
                'role' => 'student',
            ])->givePermissionTo([
                'view courses', 'view routines', 'view attendances', 'view marks', 'submit assignments', 'view assignments', 'view notices', 'view events', 'view syllabi'
            ]);
        }
    }
}
