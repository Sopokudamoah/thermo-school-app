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

        $classes = [
            'Primary 1',
            'Primary 2',
            'Primary 3',
            'Primary 4',
            'Primary 5',
            'Primary 6',
            'JHS 1',
            'JHS 2',
            'JHS 3',
        ];

        foreach ($classes as $class) {
            \App\Models\SchoolClass::updateOrCreate([
                'class_name' => $class,
                'session_id' => $session->id,
            ]);
        }
    }
}
