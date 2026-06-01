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
        $sessions = \App\Models\SchoolSession::all();

        $courseNames = [
            'Mathematics', 'English Language', 'Integrated Science', 'Social Studies',
            'Information and Communication Technology (ICT)', 'Religious and Moral Education (RME)',
            'Creative Arts and Design', 'Career Technology', 'Our World Our People',
            'Ghanaian Language (Twi)', 'Ghanaian Language (Ga)', 'French',
            'Physical Education'
        ];

        foreach ($sessions as $session) {
            $semesters = \App\Models\Semester::where('session_id', $session->id)->get();
            $classes = \App\Models\SchoolClass::where('session_id', $session->id)->get();

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
}
