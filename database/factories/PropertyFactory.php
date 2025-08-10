<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\RealEstateOffice;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'type' => $this->faker->randomElement(['apartment', 'villa', 'land', 'office', 'commercial']),
            'price' => $this->faker->numberBetween(100000, 1000000),
            'area' => $this->faker->numberBetween(100, 1000),
            'rooms' => $this->faker->numberBetween(1, 10),
            'legal_status' => $this->faker->randomElement(['registered', 'pending', 'customary']),
            'offer_type' => $this->faker->randomElement(['sale', 'rent']),
            'status_id' => Status::inRandomOrder()->value('id'),
            'contact_visible' => $this->faker->boolean,
            'user_id' => User::inRandomOrder()->value('id'),
            'real_estate_office_id' => RealEstateOffice::inRandomOrder()->value('id'),
            'location_id' => Location::inRandomOrder()->value('id'),
	    ];
    }
}
