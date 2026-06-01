<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $ghanaianFirstNames = [
            'Kwame',
            'Kwesi',
            'Kofi',
            'Kwabena',
            'Kweku',
            'Yaw',
            'Kojo',
            'Ama',
            'Esi',
            'Afia',
            'Abena',
            'Akua',
            'Yaa',
            'Adwoa',
            'Nana',
            'Owusu',
            'Osei',
            'Appiah',
            'Mensah',
            'Annan',
            'Ekow',
            'Baaba',
            'Araba',
            'Kuuku',
            'Fiifi'
        ];

        $ghanaianLastNames = [
            'Mensah',
            'Asare',
            'Oppong',
            'Agyapong',
            'Appiah',
            'Osei',
            'Danquah',
            'Quansah',
            'Adu',
            'Boateng',
            'Kyeremateng',
            'Akufo-Addo',
            'Mahama',
            'Rawlings',
            'Kufuor',
            'Atta-Mills',
            'Donkor',
            'Gyan',
            'Ayew',
            'Atsu',
            'Partey'
        ];

        $ghanaianCities = [
            'Accra',
            'Kumasi',
            'Tamale',
            'Takoradi',
            'Cape Coast',
            'Koforidua',
            'Sunyani',
            'Ho',
            'Bolgatanga',
            'Wa'
        ];

        return [
            'reference_code' => Student::generateUniqueReferenceCode(),
            'first_name' => $this->faker->randomElement($ghanaianFirstNames),
            'last_name' => $this->faker->randomElement($ghanaianLastNames),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => '0' . $this->faker->numberBetween(20, 59) . $this->faker->numerify('#######'),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'nationality' => 'Ghanaian',
            'address' => $this->faker->streetAddress(),
            'address2' => $this->faker->secondaryAddress(),
            'city' => $this->faker->randomElement($ghanaianCities),
            'zip' => $this->faker->postcode(),
            'photo' => null,
            'birthday' => $this->faker->date('Y-m-d', '-5 years'),
            'religion' => $this->faker->randomElement(['Christianity', 'Islam', 'Traditional', 'None']),
            'blood_type' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
        ];
    }
}
