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
        \App\Models\SchoolSession::updateOrCreate([
            'session_name' => '2023-2024',
        ], [
            'start_date' => '2023-09-01',
            'end_date' => '2024-08-31'
        ]);

        \App\Models\SchoolSession::updateOrCreate([
            'session_name' => '2024-2025',
        ], [
            'start_date' => '2024-09-01',
            'end_date' => '2025-08-31'
        ]);
    }
}
