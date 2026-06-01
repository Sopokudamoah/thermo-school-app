<?php
namespace Database\Seeders;

use App\Models\AssignedTeacher;
use App\Models\Course;
use App\Models\SchoolClass;
use App\Models\SchoolSession;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssignedTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teachers = User::where('role', 'teacher')->get();

        if ($teachers->isEmpty()) {
            return;
        }

        $sessions = SchoolSession::all();
        $teacherAssignmentCount = [];

        foreach ($sessions as $session) {
            $classes = SchoolClass::where('session_id', $session->id)->get();

            foreach ($classes as $class) {
                $sections = Section::where('class_id', $class->id)->get();
                $courses = Course::where('class_id', $class->id)->get();

                foreach ($sections as $section) {
                    foreach ($courses as $course) {
                        // Filter teachers who have not reached the limit of 4 courses in total
                        $eligibleTeachers = $teachers->filter(function ($teacher) use ($teacherAssignmentCount) {
                            return ($teacherAssignmentCount[$teacher->id] ?? 0) < 4;
                        });

                        if ($eligibleTeachers->isEmpty()) {
                            // If all teachers reached the limit, we skip further assignments
                            continue;
                        }

                        $teacher = $eligibleTeachers->random();

                        AssignedTeacher::updateOrCreate([
                            'teacher_id' => $teacher->id,
                            'semester_id' => $course->semester_id,
                            'class_id' => $class->id,
                            'section_id' => $section->id,
                            'course_id' => $course->id,
                            'session_id' => $session->id,
                        ]);

                        $teacherAssignmentCount[$teacher->id] = ($teacherAssignmentCount[$teacher->id] ?? 0) + 1;
                    }
                }
            }
        }
    }
}
