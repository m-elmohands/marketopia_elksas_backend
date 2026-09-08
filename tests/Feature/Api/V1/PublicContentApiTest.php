<?php

namespace Tests\Feature\Api\V1;

use App\Models\Content\Article;
use App\Models\Content\Category;
use App\Models\Content\Motorcycle;
use App\Models\Content\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicContentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_returns_content_in_requested_locale(): void
    {
        Storage::fake('public');
        $category = Category::factory()->create(['ar' => ['name' => 'رياضية'], 'en' => ['name' => 'Sport']]);
        Motorcycle::factory()->for($category)->create(['is_featured' => true]);
        Article::factory()->for($category)->create(['is_featured' => true]);
        $logo = SiteSetting::query()->create(['key' => 'site_logo', 'type' => 'image']);
        $logo->addMedia(database_path('seeders/media/branding/el-ksas-logo.jpg'))->preservingOriginal()->toMediaCollection('logo');

        $this->getJson('/api/v1/home?locale=en')
            ->assertOk()->assertJsonStructure(['data' => ['settings', 'categories', 'motorcycles', 'articles'], 'message', 'errors'])
            ->assertJsonPath('data.categories.0.name', 'Sport')
            ->assertJsonPath('data.settings.site_logo_url', $logo->getFirstMediaUrl('logo'));
    }

    public function test_list_endpoints_exclude_unpublished_content(): void
    {
        $category = Category::factory()->create();
        Motorcycle::factory()->for($category)->create(['published_at' => null]);
        Article::factory()->for($category)->create(['published_at' => null]);

        $this->getJson('/api/v1/motorcycles?locale=ar')->assertOk()->assertJsonCount(0, 'data');
        $this->getJson('/api/v1/articles?locale=ar')->assertOk()->assertJsonCount(0, 'data');
    }
}
