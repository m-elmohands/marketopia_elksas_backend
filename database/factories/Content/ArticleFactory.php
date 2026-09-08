<?php

namespace Database\Factories\Content;

use App\Models\Content\Article;
use App\Models\Content\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
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
            'ar' => ['title' => fake()->sentence(4), 'excerpt' => fake()->paragraph(), 'body' => fake()->paragraphs(5, true)],
            'en' => ['title' => fake()->sentence(4), 'excerpt' => fake()->paragraph(), 'body' => fake()->paragraphs(5, true)],
            'is_featured' => false,
            'published_at' => now(),
        ];
    }
}
