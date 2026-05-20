<?php

namespace Database\Factories;

use App\Models\Semester;
use Illuminate\Database\Eloquent\Factories\Factory;

class SemesterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Semester::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'semester_name' => $this->faker->randomElement(['First Semester', 'Second Semester', 'Summer Semester']),
            'start_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+6 months'),
            'session_id' => \App\Models\SchoolSession::factory(),
        ];
    }
}
