<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class GiftFactory extends Factory
{
    public function definition(): array
    {
        return [
            'profile_id' => Profile::factory(),
            'title' => fake()->words(3, true),
            'description' => fake()->optional()->sentence(),
            'link' => fake()->optional()->url(),
            'is_list' => false,
        ];
    }

    public function list(): static
    {
        return $this->state(fn () => ['is_list' => true]);
    }
}
