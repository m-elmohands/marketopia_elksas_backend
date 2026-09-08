<?php

namespace Database\Seeders;

use App\Actions\Content\AttachSeedMedia;
use App\Models\Content\Article;
use App\Models\Content\Category;
use App\Models\Content\Motorcycle;
use App\Models\Content\SiteSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ContentSeeder extends Seeder
{
    public function __construct(private readonly AttachSeedMedia $attachSeedMedia) {}

    public function run(): void
    {
        $categories = collect([
            ['slug' => 'sport-motorcycles', 'sort_order' => 1, 'ar' => ['name' => 'دراجات رياضية', 'description' => 'أداء قوي وتحكم دقيق لمحبي السرعة.'], 'en' => ['name' => 'Sport Motorcycles', 'description' => 'Powerful performance and precise handling for speed enthusiasts.']],
            ['slug' => 'street-motorcycles', 'sort_order' => 2, 'ar' => ['name' => 'دراجات الشوارع', 'description' => 'خيارات عملية ومريحة للاستخدام اليومي داخل المدينة.'], 'en' => ['name' => 'Street Motorcycles', 'description' => 'Practical and comfortable choices for daily city riding.']],
            ['slug' => 'adventure-motorcycles', 'sort_order' => 3, 'ar' => ['name' => 'دراجات المغامرات', 'description' => 'مصممة للرحلات الطويلة والطرق المتنوعة.'], 'en' => ['name' => 'Adventure Motorcycles', 'description' => 'Built for long journeys and varied terrain.']],
        ])->mapWithKeys(fn (array $attributes) => [
            $attributes['slug'] => $this->upsertTranslated(Category::class, 'slug', $attributes),
        ]);

        $this->seedMotorcycles($categories->all());
        $this->seedArticles($categories->all());
        $this->seedSettings();
    }

    /** @param array<string, Category> $categories */
    private function seedMotorcycles(array $categories): void
    {
        $motorcycles = [
            ['category' => 'sport-motorcycles', 'media' => 'facebook/737494207_1444987114319082_6172363879074541422_n.jpg', 'slug' => 'honda-cbr-600rr', 'manufacturer' => 'Honda', 'model_year' => 2025, 'engine_cc' => 599, 'power_hp' => 121, 'price' => 780000, 'ar' => ['name' => 'هوندا CBR 600RR', 'summary' => 'دراجة رياضية متوازنة تجمع بين القوة والتحكم.', 'details' => 'تقدم استجابة دقيقة للمحرك وهيكلًا خفيفًا مناسبًا للحلبة والطريق.'], 'en' => ['name' => 'Honda CBR 600RR', 'summary' => 'A balanced sport bike combining power and precise handling.', 'details' => 'It offers responsive power delivery and a lightweight chassis for track and road riding.']],
            ['category' => 'street-motorcycles', 'media' => 'facebook/743203843_1457649476386179_3721331080783286302_n.jpg', 'slug' => 'yamaha-mt-07', 'manufacturer' => 'Yamaha', 'model_year' => 2025, 'engine_cc' => 689, 'power_hp' => 73, 'price' => 540000, 'ar' => ['name' => 'ياماها MT-07', 'summary' => 'دراجة خفيفة ومرنة مثالية للمدينة والرحلات القصيرة.', 'details' => 'محرك ثنائي الأسطوانات بعزم قوي ووضعية قيادة مريحة وسهلة التحكم.'], 'en' => ['name' => 'Yamaha MT-07', 'summary' => 'A light and agile motorcycle ideal for cities and short trips.', 'details' => 'Its twin-cylinder engine delivers strong torque with a comfortable, approachable riding position.']],
            ['category' => 'adventure-motorcycles', 'media' => 'facebook/749419764_1457651129719347_2651614620075015477_n.jpg', 'slug' => 'bmw-r-1300-gs', 'manufacturer' => 'BMW', 'model_year' => 2025, 'engine_cc' => 1300, 'power_hp' => 145, 'price' => 1450000, 'ar' => ['name' => 'بي إم دبليو R 1300 GS', 'summary' => 'رفيق متطور للرحلات الطويلة والمغامرات.', 'details' => 'تجمع بين القوة والراحة والتقنيات المساعدة للقيادة على الطرق المختلفة.'], 'en' => ['name' => 'BMW R 1300 GS', 'summary' => 'An advanced companion for long-distance adventure riding.', 'details' => 'It combines power, comfort, and rider assistance technology for varied terrain.']],
        ];

        foreach ($motorcycles as $motorcycle) {
            $category = $categories[$motorcycle['category']];
            $media = $motorcycle['media'];
            unset($motorcycle['category'], $motorcycle['media']);
            $attributes = [...$motorcycle, 'category_id' => $category->id, 'currency' => 'EGP', 'is_featured' => true, 'published_at' => now()];
            $record = $this->upsertTranslated(Motorcycle::class, 'slug', $attributes);
            $this->attachSeedMedia->execute($record, 'gallery', $media);
        }
    }

    /** @param array<string, Category> $categories */
    private function seedArticles(array $categories): void
    {
        $articles = [
            ['category' => 'street-motorcycles', 'media' => 'facebook/763894985_1472160794935047_5137390117579553984_n.jpg', 'slug' => 'first-motorcycle-buying-guide', 'ar' => ['title' => 'كيف تختار دراجتك الأولى؟', 'excerpt' => 'خطوات عملية لاختيار دراجة مناسبة لخبرتك واستخدامك.', 'body' => 'ابدأ بتحديد استخدامك اليومي، ثم اختر سعة محرك يمكن التحكم بها، ولا تتنازل عن معدات الأمان أو تجربة القيادة.'], 'en' => ['title' => 'How to choose your first motorcycle', 'excerpt' => 'Practical steps for choosing a motorcycle that suits your experience and needs.', 'body' => 'Start by defining your daily use, choose a manageable engine size, and never compromise on safety equipment or a test ride.']],
            ['category' => 'sport-motorcycles', 'media' => 'facebook/765732601_1472156471602146_9118481195247116442_n.jpg', 'slug' => 'motorcycle-braking-basics', 'ar' => ['title' => 'أساسيات الفرملة الآمنة', 'excerpt' => 'تعرف على توزيع قوة الفرامل والتوقف بثبات.', 'body' => 'استخدم الفرامل الأمامية والخلفية تدريجيًا، وحافظ على النظر للأمام واترك مسافة كافية للتوقف.'], 'en' => ['title' => 'Motorcycle braking fundamentals', 'excerpt' => 'Understand brake balance and how to stop with stability.', 'body' => 'Apply both brakes progressively, keep looking ahead, and always maintain enough stopping distance.']],
            ['category' => 'adventure-motorcycles', 'media' => 'facebook/489817185_1083158943835236_1325783514934329099_n.jpg', 'slug' => 'pre-ride-maintenance-checklist', 'ar' => ['title' => 'قائمة الفحص قبل الانطلاق', 'excerpt' => 'فحص سريع للإطارات والسوائل والسلسلة قبل كل رحلة.', 'body' => 'راجع ضغط الإطارات ومستوى الزيت والفرامل والإضاءة وشد السلسلة قبل بدء الرحلة.'], 'en' => ['title' => 'Pre-ride maintenance checklist', 'excerpt' => 'A quick inspection of tires, fluids, and chain before every journey.', 'body' => 'Check tire pressure, oil level, brakes, lights, and chain tension before setting off.']],
        ];

        foreach ($articles as $article) {
            $category = $categories[$article['category']];
            $media = $article['media'];
            unset($article['category'], $article['media']);
            $attributes = [...$article, 'category_id' => $category->id, 'is_featured' => true, 'published_at' => now()];
            $record = $this->upsertTranslated(Article::class, 'slug', $attributes);
            $this->attachSeedMedia->execute($record, 'cover', $media);
        }
    }

    private function seedSettings(): void
    {
        $settings = [
            ['key' => 'hero_title', 'type' => 'text', 'ar' => ['value' => 'كل ما تحتاجه لعالم الدراجات النارية'], 'en' => ['value' => 'Everything you need for the motorcycle world']],
            ['key' => 'hero_description', 'type' => 'textarea', 'ar' => ['value' => 'مراجعات صادقة، أدلة شراء، ونصائح صيانة من قلب الطريق.'], 'en' => ['value' => 'Honest reviews, buying guides, and maintenance advice from the road.']],
            ['key' => 'facebook_url', 'type' => 'url', 'ar' => ['value' => 'https://www.facebook.com/profile.php?id=100064234203552&locale=ar_AR'], 'en' => ['value' => 'https://www.facebook.com/profile.php?id=100064234203552&locale=ar_AR']],
        ];

        foreach ($settings as $setting) {
            $this->upsertTranslated(SiteSetting::class, 'key', $setting);
        }

        $logo = SiteSetting::query()->updateOrCreate(['key' => 'site_logo'], ['type' => 'image']);
        $this->attachSeedMedia->execute($logo, 'logo', 'branding/el-ksas-logo.jpg');
    }

    /**
     * @template TModel of Model
     *
     * @param  class-string<TModel>  $modelClass
     * @param  array<string, mixed>  $attributes
     * @return TModel
     */
    private function upsertTranslated(string $modelClass, string $identityKey, array $attributes): Model
    {
        $translations = Arr::only($attributes, ['ar', 'en']);
        $baseAttributes = Arr::except($attributes, ['ar', 'en']);

        $model = $modelClass::query()->updateOrCreate(
            [$identityKey => $baseAttributes[$identityKey]],
            $baseAttributes,
        );

        foreach ($translations as $locale => $values) {
            $translation = $model->translateOrNew($locale);
            $translation->fill($values);
            $model->translations()->save($translation);
        }

        return $model;
    }
}
