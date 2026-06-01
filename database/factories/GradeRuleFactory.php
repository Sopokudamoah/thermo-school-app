<?php

namespace Database\Factories;

use App\Models\GradeRule;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradeRuleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GradeRule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'point' => $this->faker->randomFloat(2, 0, 4),
            'grade' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'F']),
            'start_at' => 0,
            'end_at' => 100,
            'grading_system_id' => \App\Models\GradingSystem::factory(),
            'session_id' => \App\Models\SchoolSession::factory(),
        ];
    }
}
