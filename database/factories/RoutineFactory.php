<?php

namespace Database\Factories;

use App\Models\Routine;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoutineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Routine::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'start'      => $this->faker->time('H:i'),
            'end'        => $this->faker->time('H:i'),
            'weekday'    => $this->faker->numberBetween(1, 7),
            'class_id'   => 1,
            'section_id' => 1,
            'course_id'  => 1,
            'session_id' => 1,
        ];
    }
}
