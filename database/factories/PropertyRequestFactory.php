<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyRequest>
 */
class PropertyRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'property_id' => Property::inRandomOrder()->value('id'),
            'customer_id' => User::where('user_type', 'customer')->inRandomOrder()->value('id'),
            'status_id' => Status::where('entity_type', 'property_request')->inRandomOrder()->value('id'),
            'description' => $this->faker->paragraph(),
            'request_type' => $this->faker->randomElement(['sale', 'rent']),
            'budget' => $this->faker->numberBetween(100000, 1000000),
        ];
    }
}
