<?php

namespace Database\Factories;

use App\Models\FinishingRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinishingRequestImage>
 */
class FinishingRequestImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'finishing_request_id' => FinishingRequest::inRandomOrder()->value('id'),
            'image_url' => $this->faker->imageUrl(),
        ];
    }
}
