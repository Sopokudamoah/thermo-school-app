<?php
namespace Database\Seeders;

use App\Models\AssignedTeacher;
use App\Models\Course;
use App\Models\SchoolClass;
use App\Models\SchoolSession;
use App\Models\Section;
use App\Models\Semester;
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

        foreach ($sessions as $session) {
            $semesters = Semester::where('session_id', $session->id)->get();
            $classes = SchoolClass::where('session_id', $session->id)->get();

            foreach ($classes as $class) {
                $sections = Section::where('class_id', $class->id)->get();
                $courses = Course::where('class_id', $class->id)->get();

                foreach ($sections as $section) {
                    foreach ($courses as $course) {
                        // For each course in each section, assign a random teacher
                        // To make it realistic, we use the same teacher for both semesters if applicable,
                        // or just assign to the semester associated with the course.

                        $teacher = $teachers->random();

                        AssignedTeacher::updateOrCreate([
                            'teacher_id' => $teacher->id,
                            'semester_id' => $course->semester_id,
                            'class_id' => $class->id,
                            'section_id' => $section->id,
                            'course_id' => $course->id,
                            'session_id' => $session->id,
                        ]);
                    }
                }
            }
        }
    }
}
