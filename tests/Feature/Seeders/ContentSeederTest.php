<?php

namespace Tests\Feature\Seeders;

use App\Models\Content\Article;
use App\Models\Content\Category;
use App\Models\Content\Motorcycle;
use App\Models\Content\SiteSetting;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContentSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_it_explicitly_seeds_both_translations_and_is_repeatable(): void
    {
        $this->seed(ContentSeeder::class);
        $this->seed(ContentSeeder::class);

        $this->assertDatabaseCount('categories', 3);
        $this->assertDatabaseCount('category_translations', 6);
        $this->assertDatabaseCount('motorcycle_translations', 6);
        $this->assertDatabaseCount('article_translations', 6);
        $this->assertDatabaseCount('site_setting_translations', 6);
        $this->assertDatabaseCount('media', 7);

        $this->assertSame('دراجات رياضية', Category::query()->where('slug', 'sport-motorcycles')->firstOrFail()->translate('ar')->name);
        $this->assertSame('Honda CBR 600RR', Motorcycle::query()->where('slug', 'honda-cbr-600rr')->firstOrFail()->translate('en')->name);
        $this->assertSame('كيف تختار دراجتك الأولى؟', Article::query()->where('slug', 'first-motorcycle-buying-guide')->firstOrFail()->translate('ar')->title);
        $this->assertSame('Everything you need for the motorcycle world', SiteSetting::query()->where('key', 'hero_title')->firstOrFail()->translate('en')->value);
        $this->assertSame('facebook', Motorcycle::query()->where('slug', 'honda-cbr-600rr')->firstOrFail()->getFirstMedia('gallery')?->getCustomProperty('source'));
        $this->assertNotNull(Article::query()->where('slug', 'first-motorcycle-buying-guide')->firstOrFail()->getFirstMedia('cover'));
        $this->assertNotNull(SiteSetting::query()->where('key', 'site_logo')->firstOrFail()->getFirstMedia('logo'));
    }
}
