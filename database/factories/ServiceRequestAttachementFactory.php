<?php

namespace Database\Factories;

use App\Models\ServiceRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceRequestAttachement>
 */
class ServiceRequestAttachementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_request_id' => ServiceRequest::inRandomOrder()->value('id'),
            'attachment_url' => $this->faker->imageUrl(),
            'attachment_type' => $this->faker->randomElement(['image', 'document', 'video']),
        ];
    }
}
