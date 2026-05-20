<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $session = \App\Models\SchoolSession::first() ?? \App\Models\SchoolSession::factory()->create();
        $semesters = \App\Models\Semester::where('session_id', $session->id)->get();
        $classes = \App\Models\SchoolClass::where('session_id', $session->id)->get();

        $courseNames = ['Mathematics', 'English', 'Science', 'History', 'Geography', 'Art', 'Music', 'Physical Education'];

        foreach ($classes as $class) {
            foreach ($semesters as $semester) {
                foreach ($courseNames as $courseName) {
                    \App\Models\Course::factory()->create([
                        'course_name' => $courseName,
                        'class_id' => $class->id,
                        'semester_id' => $semester->id,
                        'session_id' => $session->id,
                    ]);
                }
            }
        }
    }
}
