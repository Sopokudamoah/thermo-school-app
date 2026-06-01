<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Routine;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Database\Seeder;

class RoutineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = SchoolClass::all();

        foreach ($classes as $class) {
            $sections = Section::where('class_id', $class->id)->get();
            $courses = Course::where('class_id', $class->id)->get();

            if ($sections->isEmpty() || $courses->isEmpty()) {
                continue;
            }

            foreach ($sections as $section) {
                // Add some routine periods for each section
                // Monday to Friday
                for ($day = 1; $day <= 5; $day++) {
                    // Period 1: 08:00 - 09:00
                    if ($courses->count() > 0) {
                        Routine::updateOrCreate([
                            'weekday' => $day,
                            'session_id' => $section->session_id,
                            'class_id' => $class->id,
                            'section_id' => $section->id,
                            'start' => '08:00',
                            'end' => '09:00',
                        ], [
                            'course_id' => $courses->random()->id,
                        ]);
                    }

                    // Period 2: 09:00 - 10:00
                    if ($courses->count() > 1) {
                        Routine::updateOrCreate([
                            'weekday' => $day,
                            'session_id' => $section->session_id,
                            'class_id' => $class->id,
                            'section_id' => $section->id,
                            'start' => '09:00',
                            'end' => '10:00',
                        ], [
                            'course_id' => $courses->random()->id,
                        ]);
                    }

                    // Period 3: 10:30 - 11:30 (After a break)
                    if ($courses->count() > 0) {
                        Routine::updateOrCreate([
                            'weekday' => $day,
                            'session_id' => $section->session_id,
                            'class_id' => $class->id,
                            'section_id' => $section->id,
                            'start' => '10:30',
                            'end' => '11:30',
                        ], [
                            'course_id' => $courses->random()->id,
                        ]);
                    }
                }
            }
        }
    }
}
