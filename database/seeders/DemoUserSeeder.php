<?php

namespace Database\Seeders;

use App\Models\Promotion;
use App\Models\SchoolClass;
use App\Models\SchoolSession;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAcademicInfo;
use App\Models\StudentParentInfo;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!config('app.demo_mode')) {
            return;
        }

        $sessions = SchoolSession::all();
        if ($sessions->isEmpty()) {
            $sessions = collect([SchoolSession::factory()->create(['session_name' => '2024-2025'])]);
        }

        $latestSession = $sessions->sortByDesc('id')->first();
        $previousSession = $sessions->count() > 1 ? $sessions->sortByDesc('id')->skip(1)->first() : null;

        // 1. Create Teachers
        if (User::where('role', 'teacher')->count() == 0) {
            $teachers = User::factory()->count(20)->create(['role' => 'teacher']);
            foreach ($teachers as $teacher) {
                $teacher->givePermissionTo([
                    'view courses', 'view classes', 'view sections', 'view routines', 'take attendances', 'view attendances', 'save marks', 'view marks', 'create assignments', 'view assignments', 'view notices', 'view events', 'view syllabi'
                ]);
            }
        }
        $allTeachers = User::where('role', 'teacher')->get();

        // 2. Create Students and Promote
        if (Student::count() == 0) {
            $students = Student::factory()->count(100)->create();

            foreach ($students as $student) {
                StudentParentInfo::factory()->create(['student_id' => $student->id]);
                StudentAcademicInfo::factory()->create(['student_id' => $student->id]);

                // Promote to previous session if available
                if ($previousSession) {
                    $classes = SchoolClass::where('session_id', $previousSession->id)->get();
                    if ($classes->isNotEmpty()) {
                        $class = $classes->random();
                        $sections = Section::where('class_id', $class->id)->get();
                        if ($sections->isNotEmpty()) {
                            $section = $sections->random();
                            Promotion::factory()->create([
                                'student_id' => $student->id,
                                'class_id'   => $class->id,
                                'section_id' => $section->id,
                                'session_id' => $previousSession->id,
                            ]);
                        }
                    }
                } else {
                    // If no previous session, still need to be in AT LEAST one session to show up in lists
                    // But for the sake of "Promote" demo, they should be in the previous session if we want to promote them to latest.
                    // The seeder currently creates multiple sessions in SchoolSessionSeeder, so $previousSession should exist.
                }

                // Promote to latest session
                $shouldPromote = rand(1, 10) <= 7; // 70% chance to be promoted to latest session
                if ($shouldPromote) {
                    $classes = SchoolClass::where('session_id', $latestSession->id)->get();
                    if ($classes->isNotEmpty()) {
                        $class = $classes->random();
                        $sections = Section::where('class_id', $class->id)->get();
                        if ($sections->isNotEmpty()) {
                            $section = $sections->random();
                            Promotion::factory()->create([
                                'student_id' => $student->id,
                                'class_id' => $class->id,
                                'section_id' => $section->id,
                                'session_id' => $latestSession->id,
                            ]);
                        }
                    }
                }
            }
        }

        // 3. Create Demo Teacher and Assign Courses
        $demoTeacher = User::where('email', 'teacher@ut.com')->first();
        if (!$demoTeacher) {
            $demoTeacher = User::factory()->create([
                'first_name' => 'John',
                'last_name' => 'Teacher',
                'email' => 'teacher@ut.com',
                'role' => 'teacher',
            ]);
            $demoTeacher->givePermissionTo([
                'view courses', 'view classes', 'view sections', 'view routines', 'take attendances', 'view attendances', 'save marks', 'view marks', 'create assignments', 'view assignments', 'view notices', 'view events', 'view syllabi'
            ]);
        }

        // Assign demo teacher to some courses in the latest session
        $semesters = \App\Models\Semester::where('session_id', $latestSession->id)->get();
        $classes = SchoolClass::where('session_id', $latestSession->id)->get();

        foreach ($classes as $index => $class) {
            // Assign a unique teacher to this class for demo variety
            $classTeacher = $allTeachers->get($index % $allTeachers->count());

            $sections = Section::where('class_id', $class->id)->get();
            foreach ($sections as $section) {
                foreach ($semesters as $semester) {
                    // Assign the specific class teacher to at least one course in each section/semester
                    $firstCourse = \App\Models\Course::where('class_id', $class->id)
                        ->where('semester_id', $semester->id)
                        ->first();

                    if ($firstCourse) {
                        \App\Models\AssignedTeacher::updateOrCreate([
                            'teacher_id' => $classTeacher->id,
                            'semester_id' => $semester->id,
                            'class_id' => $class->id,
                            'section_id' => $section->id,
                            'course_id' => $firstCourse->id,
                            'session_id' => $latestSession->id,
                        ]);
                    }

                    // Also assign the main demo teacher to some courses to ensure the demo login works as expected
                    $courses = \App\Models\Course::where('class_id', $class->id)
                        ->where('semester_id', $semester->id)
                        ->limit(3)
                        ->get();

                    foreach ($courses as $course) {
                        \App\Models\AssignedTeacher::updateOrCreate([
                            'teacher_id' => $demoTeacher->id,
                            'semester_id' => $semester->id,
                            'class_id' => $class->id,
                            'section_id' => $section->id,
                            'course_id' => $course->id,
                            'session_id' => $latestSession->id,
                        ]);
                    }
                }
            }
        }

        // 4. Create Demo Student
        if (!Student::where('email', 'student@ut.com')->exists()) {
            $demoStudent = Student::factory()->create([
                'first_name' => 'Jane',
                'last_name' => 'Student',
                'email' => 'student@ut.com',
            ]);

            StudentParentInfo::factory()->create(['student_id' => $demoStudent->id]);
            StudentAcademicInfo::factory()->create(['student_id' => $demoStudent->id]);

            // Promote demo student to latest session
            $classes = SchoolClass::where('session_id', $latestSession->id)->get();
            if ($classes->isNotEmpty()) {
                $class = $classes->random();
                $sections = Section::where('class_id', $class->id)->get();
                if ($sections->isNotEmpty()) {
                    $section = $sections->random();
                    Promotion::factory()->create([
                        'student_id' => $demoStudent->id,
                        'class_id' => $class->id,
                        'section_id' => $section->id,
                        'session_id' => $latestSession->id,
                    ]);
                }
            }
        }
    }
}
