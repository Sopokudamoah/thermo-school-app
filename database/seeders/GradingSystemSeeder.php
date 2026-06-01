<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GradingSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sessions = \App\Models\SchoolSession::all();

        foreach ($sessions as $session) {
            $semesters = \App\Models\Semester::where('session_id', $session->id)->get();
            $classes = \App\Models\SchoolClass::where('session_id', $session->id)->get();

            foreach ($semesters as $semester) {
                foreach ($classes as $class) {
                    \App\Models\GradingSystem::updateOrCreate([
                        'class_id' => $class->id,
                        'semester_id' => $semester->id,
                        'session_id' => $session->id,
                    ], [
                        'system_name' => 'Standard Grading System',
                    ]);
                }
            }
        }
    }
}
