<?php

namespace Database\Factories;

use App\Models\Notice;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoticeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $notices = [
            'All students are reminded to wear their full school uniform for the Independence Day parade.',
            'Mid-term break starts this Friday. School resumes on Tuesday.',
            'PTA Meeting scheduled for next Saturday at 10:00 AM in the school hall.',
            'Entrance examinations for new students will be held on the 15th of next month.',
            'The school will be closed on Friday for the Farmer\'s Day holiday.',
            'All JHS 3 students should submit their BECE registration forms by Wednesday.',
            'Reminder: School fees for the second term should be paid by the end of this week.',
            'Inter-school sports competition: All participants should meet at the field after school.',
            'New ICT laboratory rules are now posted on the lab door. Please read them carefully.',
            'Speech and Prize Giving Day rehearsal will take place tomorrow at 2:00 PM.'
        ];

        return [
            'notice' => $this->faker->randomElement($notices),
            'session_id' => \App\Models\SchoolSession::first()?->id ?? \App\Models\SchoolSession::factory(),
        ];
    }
}
