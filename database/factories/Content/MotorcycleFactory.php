<?php

namespace Database\Factories\Content;

use App\Models\Content\Category;
use App\Models\Content\Motorcycle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Motorcycle>
 */
class MotorcycleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'slug' => fake()->unique()->slug(),
            'manufacturer' => fake()->randomElement(['Honda', 'Yamaha', 'Kawasaki', 'BMW']),
            'model_year' => fake()->numberBetween(2020, 2026),
            'engine_cc' => fake()->randomElement([150, 300, 600, 1000]),
            'power_hp' => fake()->numberBetween(15, 180),
            'price' => fake()->numberBetween(80000, 900000),
            'currency' => 'EGP',
            'ar' => ['name' => fake()->words(2, true), 'summary' => fake()->paragraph(), 'details' => fake()->paragraphs(4, true)],
            'en' => ['name' => fake()->words(2, true), 'summary' => fake()->paragraph(), 'details' => fake()->paragraphs(4, true)],
            'is_featured' => false,
            'published_at' => now(),
        ];
    }
}
