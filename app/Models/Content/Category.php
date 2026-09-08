<?php

namespace App\Models\Content;

use App\Models\Concerns\AuditsContentChanges;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Database\Factories\Content\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model implements TranslatableContract
{
    /** @use HasFactory<CategoryFactory> */
    use AuditsContentChanges, HasFactory, SoftDeletes, Translatable;

    public array $translatedAttributes = ['name', 'description'];

    protected $fillable = ['slug', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function motorcycles(): HasMany
    {
        return $this->hasMany(Motorcycle::class);
    }
}
