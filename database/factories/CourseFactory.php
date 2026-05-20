<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'course_name' => $this->faker->randomElement(['Mathematics', 'English', 'Science', 'History', 'Geography', 'Art', 'Music', 'Physical Education']),
            'course_type' => $this->faker->randomElement(['Theory', 'Practical']),
            'class_id' => \App\Models\SchoolClass::factory(),
            'semester_id' => \App\Models\Semester::factory(),
            'session_id' => \App\Models\SchoolSession::factory(),
        ];
    }
}
