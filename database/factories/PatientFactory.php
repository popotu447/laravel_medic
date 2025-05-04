<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'firstName' => $this->faker->firstName(),
            'lastName' => $this->faker->lastName(),
            'birthDate' => $this->faker->dateTimeBetween('1950-01-01', '2005-12-31')->format('Y-m-d'),
            'gender' => $this->faker->randomElement(['M', 'F']),
        ];
    }
}
