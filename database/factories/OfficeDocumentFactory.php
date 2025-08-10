<?php

namespace Database\Factories;

use App\Models\OfficeDocument;
use App\Models\RealEstateOffice;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfficeDocumentFactory extends Factory
{
    protected $model = OfficeDocument::class;

    public function definition(): array
    {
        return [
            'real_estate_office_id' => RealEstateOffice::factory(),
            'document_type' => $this->faker->randomElement(['license', 'certificate', 'insurance']),
            'file_path' => 'office-documents/' . $this->faker->uuid . '.pdf',
            'original_filename' => $this->faker->word . '.pdf',
            'mime_type' => 'application/pdf',
            'description' => $this->faker->sentence,
        ];
    }
} 