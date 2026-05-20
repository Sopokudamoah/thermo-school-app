<?php

namespace Database\Factories;

use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolClassFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SchoolClass::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'class_name' => $this->faker->randomElement(['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5']),
            'session_id' => \App\Models\SchoolSession::factory(),
        ];
    }
}
