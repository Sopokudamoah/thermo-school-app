<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GradeRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sessions = \App\Models\SchoolSession::all();

        $rules = [
            ['grade' => 'A', 'point' => 4.0, 'start_at' => 80, 'end_at' => 100],
            ['grade' => 'B', 'point' => 3.0, 'start_at' => 70, 'end_at' => 79.99],
            ['grade' => 'C', 'point' => 2.0, 'start_at' => 60, 'end_at' => 69.99],
            ['grade' => 'D', 'point' => 1.0, 'start_at' => 50, 'end_at' => 59.99],
            ['grade' => 'F', 'point' => 0.0, 'start_at' => 0,  'end_at' => 49.99],
        ];

        foreach ($sessions as $session) {
            $gradingSystems = \App\Models\GradingSystem::where('session_id', $session->id)->get();

            foreach ($gradingSystems as $system) {
                foreach ($rules as $rule) {
                    \App\Models\GradeRule::updateOrCreate([
                        'grading_system_id' => $system->id,
                        'grade' => $rule['grade'],
                        'session_id' => $session->id,
                    ], [
                        'point' => $rule['point'],
                        'start_at' => $rule['start_at'],
                        'end_at' => $rule['end_at'],
                    ]);
                }
            }
        }
    }
}
