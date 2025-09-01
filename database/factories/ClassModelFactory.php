<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassModel>
 */
class ClassModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Turma ' . fake()->word(),
            'code' => strtoupper(fake()->unique()->bothify('TUR-###')),
            'status' => random_int(1, 3),
            'start_date' => now()->subDays(random_int(0, 30)),
            'end_date' => now()->addDays(random_int(10, 60)),
            'capacity' => random_int(10, 40),
            'modality' => fake()->randomElement(['online', 'presential', 'hybrid']),
        ];
    }
}
