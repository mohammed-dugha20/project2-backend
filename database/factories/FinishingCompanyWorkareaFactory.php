<?php

namespace Database\Factories;

use App\Models\FinishingCompany;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinishingCompanyWorkarea>
 */
class FinishingCompanyWorkareaFactory extends Factory
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
            'location_id' => Location::inRandomOrder()->value('id'),
        ];
    }
}
