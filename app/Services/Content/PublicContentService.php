<?php

namespace App\Services\Content;

use App\Models\Content\Article;
use App\Models\Content\Category;
use App\Models\Content\Motorcycle;
use App\Models\Content\SiteSetting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class PublicContentService
{
    public function home(string $locale): array
    {
        return Cache::remember("home.{$locale}", now()->addMinutes(10), function () use ($locale): array {
            $settings = SiteSetting::query()->with(['translations', 'media'])->get();
            $logo = $settings->firstWhere('key', 'site_logo');

            return [
                'settings' => $settings->mapWithKeys(fn (SiteSetting $setting) => [$setting->key => $setting->translateOrDefault($locale)?->value])
                    ->put('site_logo_url', $logo?->getFirstMediaUrl('logo') ?: null),
                'categories' => Category::query()->with('translations')->where('is_active', true)->orderBy('sort_order')->get(),
                'motorcycles' => Motorcycle::query()->with(['translations', 'category.translations', 'media'])->published()->where('is_featured', true)->latest('published_at')->limit(6)->get(),
                'articles' => Article::query()->with(['translations', 'category.translations', 'media'])->published()->where('is_featured', true)->latest('published_at')->limit(6)->get(),
            ];
        });
    }

    public function articles(array $filters): LengthAwarePaginator
    {
        return Article::query()->with(['translations', 'category.translations', 'media'])->published()->search($filters['search'] ?? null)
            ->when($filters['category'] ?? null, fn ($query, $slug) => $query->whereHas('category', fn ($query) => $query->where('slug', $slug)))
            ->orderBy('published_at', ($filters['sort'] ?? 'latest') === 'oldest' ? 'asc' : 'desc')->paginate($filters['per_page'] ?? 12);
    }

    public function motorcycles(array $filters): LengthAwarePaginator
    {
        return Motorcycle::query()->with(['translations', 'category.translations', 'media'])->published()->search($filters['search'] ?? null)
            ->when($filters['category'] ?? null, fn ($query, $slug) => $query->whereHas('category', fn ($query) => $query->where('slug', $slug)))
            ->orderBy('published_at', ($filters['sort'] ?? 'latest') === 'oldest' ? 'asc' : 'desc')->paginate($filters['per_page'] ?? 12);
    }
}
