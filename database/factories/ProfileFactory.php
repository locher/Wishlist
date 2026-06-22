<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    public function definition(): array
    {
        $birthday = fake()->boolean()
            ? fake()->dateTimeBetween('-40 years', '-1 year')
            : null;

        return [
            'name' => fake()->firstName(),
            'is_child' => false,
            'avatar' => fake()->numberBetween(1, 15),
            'birthday' => $birthday?->format('Y-m-d'),
            'size_top' => null,
            'size_bottom' => null,
            'size_feet' => null,
        ];
    }

    public function child(): static
    {
        return $this->state(fn () => ['is_child' => true]);
    }
}
