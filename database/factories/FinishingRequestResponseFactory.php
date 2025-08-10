<?php

namespace Database\Factories;

use App\Models\FinishingRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinishingRequestResponse>
 */
class FinishingRequestResponseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['accepted', 'rejected']);
        
        return [
            'finishing_request_id' => FinishingRequest::factory(),
            'status' => $status,
            'reason' => $status === 'rejected' ? $this->faker->sentence() : null,
            'estimated_cost' => $status === 'accepted' ? $this->faker->randomFloat(2, 1000, 50000) : null,
            'implementation_period' => $status === 'accepted' ? $this->faker->randomElement(['2-3 weeks', '1-2 months', '3-4 months']) : null,
            'materials' => $status === 'accepted' ? $this->faker->paragraph() : null,
            'work_phases' => $status === 'accepted' ? $this->faker->paragraph() : null,
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }

    /**
     * Indicate that the response is accepted.
     */
    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'accepted',
            'reason' => null,
            'estimated_cost' => $this->faker->randomFloat(2, 1000, 50000),
            'implementation_period' => $this->faker->randomElement(['2-3 weeks', '1-2 months', '3-4 months']),
            'materials' => $this->faker->paragraph(),
            'work_phases' => $this->faker->paragraph(),
        ]);
    }

    /**
     * Indicate that the response is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'reason' => $this->faker->sentence(),
            'estimated_cost' => null,
            'implementation_period' => null,
            'materials' => null,
            'work_phases' => null,
        ]);
    }
} 