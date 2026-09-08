<?php

namespace Database\Factories\Content;

use App\Models\Content\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(),
            'ar' => ['name' => fake()->words(2, true), 'description' => fake()->sentence()],
            'en' => ['name' => fake()->words(2, true), 'description' => fake()->sentence()],
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 20),
        ];
    }
}
