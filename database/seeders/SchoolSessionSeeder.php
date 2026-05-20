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
        \App\Models\SchoolSession::factory()->create(['session_name' => '2024-2025']);
    }
}
