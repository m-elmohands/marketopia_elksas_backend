<?php

namespace Database\Factories\Content;

use App\Models\Content\SiteSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SiteSetting>
 */
class SiteSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => fake()->unique()->slug(2),
            'ar' => ['value' => fake()->sentence()],
            'en' => ['value' => fake()->sentence()],
            'type' => 'text',
        ];
    }
}
