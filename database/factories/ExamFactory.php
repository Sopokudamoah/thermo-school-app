<?php

namespace Database\Factories;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Exam::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'exam_name' => $this->faker->words(3, true),
            'start_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'class_id' => \App\Models\SchoolClass::factory(),
            'course_id' => \App\Models\Course::factory(),
            'semester_id' => \App\Models\Semester::factory(),
            'session_id' => \App\Models\SchoolSession::factory(),
        ];
    }
}
