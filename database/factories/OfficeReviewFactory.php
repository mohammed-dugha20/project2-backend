<?php

namespace Database\Factories;

use App\Models\OfficeReview;
use App\Models\RealEstateOffice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfficeReviewFactory extends Factory
{
    protected $model = OfficeReview::class;

    public function definition(): array
    {
        return [
            'real_estate_office_id' => RealEstateOffice::factory(),
            'reviewer_id' => User::factory()->create(['user_type' => 'customer'])->id,
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->paragraph,
            'is_verified' => $this->faker->boolean(80), // 80% chance of being verified
        ];
    }
} 