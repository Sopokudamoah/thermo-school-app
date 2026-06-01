<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FinalMarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $marks = \App\Models\Mark::query()->with('exam.semester')->get();
        $groupedMarks = $marks->groupBy(['student_id', 'course_id', 'exam_id', 'session_id']);

        foreach ($groupedMarks as $studentId => $courses) {
            foreach ($courses as $courseId => $semesters) {
                foreach ($semesters as $semesterId => $sessions) {
                    foreach ($sessions as $sessionId => $marksList) {
                        $averageMark = $marksList->avg('marks');
                        $firstMark = $marksList->first();
                        $semester = $firstMark->exam->semester;

                        \App\Models\FinalMark::factory()->create([
                            'student_id' => $studentId,
                            'course_id' => $courseId,
                            'semester_id' => $semester->id,
                            'session_id' => $sessionId,
                            'class_id' => $firstMark->class_id,
                            'section_id' => $firstMark->section_id,
                            'final_marks' => $averageMark,
                        ]);
                    }
                }
            }
        }
    }
}
