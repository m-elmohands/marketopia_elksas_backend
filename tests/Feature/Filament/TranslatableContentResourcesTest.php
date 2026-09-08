<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Content\Articles\Pages\EditArticle;
use App\Filament\Resources\Content\Articles\Pages\ListArticles;
use App\Filament\Resources\Content\Categories\Pages\EditCategory;
use App\Filament\Resources\Content\Categories\Pages\ListCategories;
use App\Filament\Resources\Content\Motorcycles\Pages\EditMotorcycle;
use App\Filament\Resources\Content\Motorcycles\Pages\ListMotorcycles;
use App\Models\Content\Article;
use App\Models\Content\Category;
use App\Models\Content\Motorcycle;
use App\Models\User;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class TranslatableContentResourcesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed(ContentSeeder::class);
        $this->actingAs(User::factory()->create(['is_admin' => true]));
    }

    public function test_tables_show_arabic_and_english_translations(): void
    {
        Livewire::test(ListCategories::class)
            ->assertSee('دراجات رياضية')
            ->assertSee('Sport Motorcycles');

        Livewire::test(ListMotorcycles::class)
            ->assertSee('هوندا CBR 600RR')
            ->assertSee('Honda CBR 600RR');

        Livewire::test(ListArticles::class)
            ->assertSee('كيف تختار دراجتك الأولى؟')
            ->assertSee('How to choose your first motorcycle');
    }

    public function test_edit_forms_load_arabic_and_english_translations(): void
    {
        $category = Category::query()->where('slug', 'sport-motorcycles')->firstOrFail();
        $motorcycle = Motorcycle::query()->where('slug', 'honda-cbr-600rr')->firstOrFail();
        $article = Article::query()->where('slug', 'first-motorcycle-buying-guide')->firstOrFail();

        Livewire::test(EditCategory::class, ['record' => $category->getRouteKey()])
            ->assertFormSet(['ar.name' => 'دراجات رياضية', 'en.name' => 'Sport Motorcycles']);

        Livewire::test(EditMotorcycle::class, ['record' => $motorcycle->getRouteKey()])
            ->assertFormSet(['ar.name' => 'هوندا CBR 600RR', 'en.name' => 'Honda CBR 600RR']);

        Livewire::test(EditArticle::class, ['record' => $article->getRouteKey()])
            ->assertFormSet(['ar.title' => 'كيف تختار دراجتك الأولى؟', 'en.title' => 'How to choose your first motorcycle']);
    }

    public function test_edit_form_saves_each_locale_to_its_translation_row(): void
    {
        $category = Category::query()->where('slug', 'sport-motorcycles')->firstOrFail();

        Livewire::test(EditCategory::class, ['record' => $category->getRouteKey()])
            ->fillForm([
                'ar.name' => 'دراجات الأداء',
                'en.name' => 'Performance Motorcycles',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $category->refresh();

        $this->assertSame('دراجات الأداء', $category->translate('ar')?->name);
        $this->assertSame('Performance Motorcycles', $category->translate('en')?->name);
    }
}
