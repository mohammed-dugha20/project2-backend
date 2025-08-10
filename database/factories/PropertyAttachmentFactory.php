<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\PropertyAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyAttachmentFactory extends Factory
{
    protected $model = PropertyAttachment::class;

    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'attachment_type' => $this->faker->randomElement(['deed', 'plan', 'contract', 'other']),
            'file_path' => 'property-attachments/' . $this->faker->uuid . '.pdf',
            'original_filename' => $this->faker->word . '.pdf',
            'mime_type' => 'application/pdf',
            'description' => $this->faker->sentence,
        ];
    }
} 