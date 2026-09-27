<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = \App\Models\Student::class;

    public function definition(): array
    {
        return [
            'roll_number' => 'R' . $this->faker->unique()->numberBetween(1000, 9999),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'department' => $this->faker->randomElement(['CE', 'IT', 'Mechanical', 'Civil']),
            'semester' => $this->faker->numberBetween(1, 8),
            'date_of_birth' => $this->faker->date(),
        ];
    }
}
