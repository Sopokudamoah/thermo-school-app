<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $session = \App\Models\SchoolSession::first() ?? \App\Models\SchoolSession::factory()->create();
        $classes = \App\Models\SchoolClass::all();

        if ($classes->isEmpty()) {
            $this->call(SchoolClassSeeder::class);
            $classes = \App\Models\SchoolClass::all();
        }

        foreach ($classes as $class) {
            \App\Models\Section::factory()->create([
                'section_name' => 'A',
                'class_id' => $class->id,
                'session_id' => $session->id,
            ]);
            \App\Models\Section::factory()->create([
                'section_name' => 'B',
                'class_id' => $class->id,
                'session_id' => $session->id,
            ]);
        }
    }
}
