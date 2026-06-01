<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
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
            $year = explode('-', $session->session_name)[0];

            \App\Models\Semester::factory()->create([
                'semester_name' => 'First Semester',
                'session_id' => $session->id,
                'start_date' => "$year-09-01",
                'end_date' => ($year + 1) . "-01-31",
            ]);

            \App\Models\Semester::factory()->create([
                'semester_name' => 'Second Semester',
                'session_id' => $session->id,
                'start_date' => ($year + 1) . "-02-01",
                'end_date' => ($year + 1) . "-06-30",
            ]);
        }
    }
}
