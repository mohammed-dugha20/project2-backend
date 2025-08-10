<?php

namespace Database\Factories;

use App\Models\FinishingCompanyReview;
use App\Models\FinishingCompany;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinishingCompanyReviewFactory extends Factory
{
    protected $model = FinishingCompanyReview::class;

    public function definition(): array
    {
        return [
            'finishing_company_id' => FinishingCompany::factory(),
            'reviewer_id' => User::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->sentence(12),
            'is_verified' => $this->faker->boolean(80),
        ];
    }
} 