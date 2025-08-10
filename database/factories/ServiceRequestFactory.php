<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\ServiceProvider;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceRequest>
 */
class ServiceRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_type' => $this->faker->randomElement(['lawyer', 'engineer', 'cleaning', 'painting']),
            'description' => $this->faker->sentence(),
            'budget' => $this->faker->numberBetween(100, 1000),
            'customer_id' => User::where('user_type', 'customer')->inRandomOrder()->value('id'),
            'provider_id' => ServiceProvider::inRandomOrder()->value('id'),
            'location_id' => Location::inRandomOrder()->value('id'),
            'status_id' => Status::where('entity_type', 'service_request')->inRandomOrder()->value('id'),
        ];
    }
}
