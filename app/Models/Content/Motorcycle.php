<?php

namespace App\Models\Content;

use App\Models\Concerns\AuditsContentChanges;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Database\Factories\Content\MotorcycleFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Motorcycle extends Model implements HasMedia, TranslatableContract
{
    /** @use HasFactory<MotorcycleFactory> */
    use AuditsContentChanges, HasFactory, InteractsWithMedia, SoftDeletes, Translatable;

    public array $translatedAttributes = ['name', 'summary', 'details'];

    protected $fillable = ['category_id', 'slug', 'manufacturer', 'model_year', 'engine_cc', 'power_hp', 'price', 'currency', 'is_featured', 'published_at'];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean', 'published_at' => 'datetime', 'price' => 'decimal:2'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, fn (Builder $query, string $search) => $query->where(fn (Builder $query) => $query->where('manufacturer', 'like', "%{$search}%")->orWhereHas('translations', fn (Builder $query) => $query->where('name', 'like', "%{$search}%"))));
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
