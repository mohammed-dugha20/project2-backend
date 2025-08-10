<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceProvider>
 */
class ServiceProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'professional_name' => $this->faker->name(),
            'license_info' => $this->faker->sentence(),
            'availability' => $this->faker->boolean(),
            'is_active' => $this->faker->boolean(),
            'user_id' => User::where('user_type', 'service_provider')->inRandomOrder()->value('id'),
        ];
    }
}
