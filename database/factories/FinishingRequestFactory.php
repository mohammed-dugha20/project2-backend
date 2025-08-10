<?php

namespace Database\Factories;

use App\Models\FinishingCompany;
use App\Models\Location;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinishingRequest>
 */
class FinishingRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => User::where('user_type', 'customer')->inRandomOrder()->value('id'),
            'company_id' => FinishingCompany::inRandomOrder()->value('id'),
            'location_id' => Location::inRandomOrder()->value('id'),
            'status_id' => Status::where('entity_type', 'finishing_request')->inRandomOrder()->value('id'),
            'service_type' => $this->faker->randomElement(['interior', 'exerior', 'electrical', 'plumping']),
            'description' => $this->faker->sentence(),
            'area' => $this->faker->numberBetween(50, 500),
            'rooms' => $this->faker->numberBetween(1, 10),
            'floor' => $this->faker->numberBetween(1, 10),
        ];
    }
}
