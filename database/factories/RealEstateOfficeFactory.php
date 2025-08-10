<?php

namespace Database\Factories;

use App\Models\RealEstateOffice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RealEstateOffice>
 */
class RealEstateOfficeFactory extends Factory
{
    protected $model = RealEstateOffice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'commercial_name' => $this->faker->company,
            'address' => $this->faker->address,
            'phone_number' => $this->faker->phoneNumber,
            'license_number' => $this->faker->unique()->numerify('LIC-####'),
            'profile_description' => $this->faker->paragraph,
            'is_active' => true,
            'user_id' => User::factory()->create(['user_type' => 'real_estate_office'])->id,
        ];
    }
}
