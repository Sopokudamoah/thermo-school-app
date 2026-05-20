<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $ghanaianFirstNames = [
            'Kwame', 'Kwesi', 'Kofi', 'Kwabena', 'Kweku', 'Yaw', 'Kojo',
            'Ama', 'Esi', 'Afia', 'Abena', 'Akua', 'Yaa', 'Adwoa',
            'Nana', 'Owusu', 'Osei', 'Appiah', 'Mensah', 'Annan',
            'Ekow', 'Baaba', 'Araba', 'Kuuku', 'Fiifi'
        ];

        $ghanaianLastNames = [
            'Mensah', 'Asare', 'Oppong', 'Agyapong', 'Appiah', 'Osei',
            'Danquah', 'Quansah', 'Adu', 'Boateng', 'Kyeremateng',
            'Akufo-Addo', 'Mahama', 'Rawlings', 'Kufuor', 'Atta-Mills',
            'Donkor', 'Gyan', 'Ayew', 'Atsu', 'Partey'
        ];

        $ghanaianCities = ['Accra', 'Kumasi', 'Tamale', 'Takoradi', 'Cape Coast', 'Koforidua', 'Sunyani', 'Ho', 'Bolgatanga', 'Wa'];

        return [
            'first_name' => $this->faker->randomElement($ghanaianFirstNames),
            'last_name' => $this->faker->randomElement($ghanaianLastNames),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'gender'        => $this->faker->randomElement(['Male', 'Female']),
            'nationality'   => 'Ghanaian',
            'phone'         => '0' . $this->faker->numberBetween(20, 59) . $this->faker->numerify('#######'),
            'address'       => $this->faker->streetAddress(),
            'address2'      => $this->faker->secondaryAddress(),
            'city'          => $this->faker->randomElement($ghanaianCities),
            'zip'           => $this->faker->postcode(),
            'photo'         => null,
            'role'          => 'admin',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
