<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exams = \App\Models\Exam::all();
        $promotions = \App\Models\Promotion::all();

        foreach ($exams as $exam) {
            // Find students in the same class, session, and section if course is section-specific
            // For now, let's assume courses are class-wide but we only mark students in the same session/class
            $eligiblePromotions = $promotions->where('class_id', $exam->class_id)
                ->where('session_id', $exam->session_id);

            foreach ($eligiblePromotions as $promotion) {
                \App\Models\Mark::factory()->create([
                    'student_id' => $promotion->student_id,
                    'exam_id' => $exam->id,
                    'course_id' => $exam->course_id,
                    'class_id' => $exam->class_id,
                    'section_id' => $promotion->section_id,
                    'session_id' => $exam->session_id,
                    'marks' => rand(40, 95),
                ]);
            }
        }
    }
}
