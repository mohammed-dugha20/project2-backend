<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Status>
 */
class StatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status_name' => $this->faker->word(),
            'entity_type' => $this->faker->randomElement(['property', 'property_request', 'finishing_request', 'service_request']),
            'description' => $this->faker->sentence(),
        ];
    }
}
