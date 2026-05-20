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
        $session = \App\Models\SchoolSession::first() ?? \App\Models\SchoolSession::factory()->create();

        \App\Models\Semester::factory()->create([
            'semester_name' => 'First Semester',
            'session_id' => $session->id,
            'start_date' => '2024-09-01',
            'end_date' => '2025-01-31',
        ]);

        \App\Models\Semester::factory()->create([
            'semester_name' => 'Second Semester',
            'session_id' => $session->id,
            'start_date' => '2025-02-01',
            'end_date' => '2025-06-30',
        ]);
    }
}
