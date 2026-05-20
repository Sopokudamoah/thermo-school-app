<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $events = [
            'Independence Day Celebration',
            'PTA Meeting',
            'Inter-School Sports Competition',
            'Founder\'s Day',
            'Speech and Prize Giving Day',
            'Farmer\'s Day Holiday',
            'End of Term Examinations',
            'Mid-Term Break',
            'Cultural Day',
            'Science and ICT Fair'
        ];

        $start = $this->faker->dateTimeBetween('now', '+6 months');
        $end = (clone $start)->modify('+' . rand(1, 8) . ' hours');

        return [
            'title' => $this->faker->randomElement($events),
            'start' => $start,
            'end' => $end,
            'session_id' => \App\Models\SchoolSession::first()?->id ?? \App\Models\SchoolSession::factory(),
        ];
    }
}
