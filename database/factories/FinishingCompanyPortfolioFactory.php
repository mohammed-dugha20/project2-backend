<?php

namespace Database\Factories;

use App\Models\FinishingCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinishingCompanyPortfolio>
 */
class FinishingCompanyPortfolioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'finishing_company_id' => FinishingCompany::inRandomOrder()->value('id'),
            'image_url' => $this->faker->imageUrl(),
        ];
    }
}
