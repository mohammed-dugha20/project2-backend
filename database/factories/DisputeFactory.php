<?php

namespace Database\Factories;

use App\Models\Dispute;
use App\Models\FinishingCompany;
use App\Models\FinishingRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dispute>
 */
class DisputeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => User::factory(),
            'company_id' => FinishingCompany::factory(),
            'company_type' => 'finishing_company',
            'finishing_request_id' => FinishingRequest::factory(),
            'subject' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['pending', 'investigating', 'resolved', 'closed']),
            'resolution_notes' => $this->faker->optional()->paragraph(),
            'resolved_by' => null,
            'resolved_at' => null,
        ];
    }

    /**
     * Indicate that the dispute is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'resolved_by' => null,
            'resolved_at' => null,
        ]);
    }

    /**
     * Indicate that the dispute is investigating.
     */
    public function investigating(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'investigating',
            'resolved_by' => null,
            'resolved_at' => null,
        ]);
    }

    /**
     * Indicate that the dispute is resolved.
     */
    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'resolved',
            'resolution_notes' => $this->faker->paragraph(),
            'resolved_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Indicate that the dispute is closed.
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
            'resolution_notes' => $this->faker->paragraph(),
            'resolved_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }
} 