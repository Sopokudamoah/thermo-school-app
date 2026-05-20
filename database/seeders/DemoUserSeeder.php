<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Promotion;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\SchoolSession;
use App\Models\StudentParentInfo;
use App\Models\StudentAcademicInfo;
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
        if (User::where('role', 'teacher')->count() == 0) {
            $teachers = User::factory()->count(20)->create(['role' => 'teacher']);
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
        }

        // 2. Create Students
        if (User::where('role', 'student')->count() == 0) {
            $students = User::factory()->count(100)->create(['role' => 'student']);
            $session = SchoolSession::first() ?? SchoolSession::factory()->create();
            $classes = SchoolClass::where('session_id', $session->id)->get();
            $sections = Section::where('session_id', $session->id)->get();

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

                // Create related student info
                StudentParentInfo::factory()->create(['student_id' => $student->id]);
                StudentAcademicInfo::factory()->create(['student_id' => $student->id]);

                // Promote student to a random class and section
                if ($classes->isNotEmpty() && $sections->isNotEmpty()) {
                    $class = $classes->random();
                    $section = $sections->where('class_id', $class->id)->random() ?? $sections->random();
                    Promotion::factory()->create([
                        'student_id' => $student->id,
                        'class_id'   => $class->id,
                        'section_id' => $section->id,
                        'session_id' => $session->id,
                    ]);
                }
            }
        }

        // 3. Create Parents
        if (User::where('role', 'parent')->count() == 0) {
            $parents = User::factory()->count(50)->create(['role' => 'parent']);
            foreach ($parents as $parent) {
                $parent->givePermissionTo([
                    'view attendances',
                    'view marks',
                    'view notices',
                    'view events',
                ]);
            }
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
