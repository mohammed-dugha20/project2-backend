<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceProviderWorkarea>
 */
class ServiceProviderWorkareaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provider_id' => ServiceProvider::inRandomOrder()->value('id'),
            'location_id' => Location::inRandomOrder()->value('id'),
        ];
    }
}
