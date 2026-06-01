<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SchoolSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\SchoolSession::factory()->create([
            'session_name' => '2023-2024',
            'start_date' => '2023-01-01',
            'end_date' => '2023-12-31'
        ]);
        \App\Models\SchoolSession::factory()->create([
            'session_name' => '2024-2025',
            'start_date' => '2024-01-01',
            'end_date' => '2025-12-31'
        ]);
    }
}
