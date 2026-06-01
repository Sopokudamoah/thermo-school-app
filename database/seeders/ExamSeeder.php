<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sessions = \App\Models\SchoolSession::all();

        $examNames = ['Mid-Term Examination', 'End of Semester Examination'];

        foreach ($sessions as $session) {
            $semesters = \App\Models\Semester::where('session_id', $session->id)->get();
            $classes = \App\Models\SchoolClass::where('session_id', $session->id)->get();

            foreach ($semesters as $semester) {
                foreach ($classes as $class) {
                    $courses = \App\Models\Course::where('class_id', $class->id)
                        ->where('semester_id', $semester->id)
                        ->get();

                    foreach ($examNames as $examName) {
                        foreach ($courses as $course) {
                            // Set dates based on semester
                            $startDate = $examName === 'Mid-Term Examination'
                                ? date('Y-m-d', strtotime($semester->start_date . ' + 2 months'))
                                : date('Y-m-d', strtotime($semester->end_date . ' - 2 weeks'));

                            $endDate = date('Y-m-d', strtotime($startDate . ' + 1 week'));

                            \App\Models\Exam::updateOrCreate([
                                'exam_name' => $examName,
                                'class_id' => $class->id,
                                'course_id' => $course->id,
                                'semester_id' => $semester->id,
                                'session_id' => $session->id,
                            ], [
                                'start_date' => $startDate,
                                'end_date' => $endDate,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
