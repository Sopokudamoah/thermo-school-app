<?php

namespace Database\Factories;

use App\Models\GradingSystem;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradingSystemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GradingSystem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'system_name' => $this->faker->randomElement(['Standard Grading System', 'GCSE Grading', 'University Grading']),
            'class_id' => \App\Models\SchoolClass::factory(),
            'semester_id' => \App\Models\Semester::factory(),
            'session_id' => \App\Models\SchoolSession::factory(),
        ];
    }
}
