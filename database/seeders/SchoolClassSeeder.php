<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $session = \App\Models\SchoolSession::first() ?? \App\Models\SchoolSession::factory()->create();

        $classes = ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5'];

        foreach ($classes as $class) {
            \App\Models\SchoolClass::factory()->create([
                'class_name' => $class,
                'session_id' => $session->id,
            ]);
        }
    }
}
