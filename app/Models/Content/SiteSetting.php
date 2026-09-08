<?php

namespace App\Models\Content;

use App\Models\Concerns\AuditsContentChanges;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SiteSetting extends Model implements HasMedia, TranslatableContract
{
    use AuditsContentChanges, HasFactory, InteractsWithMedia, Translatable;

    public array $translatedAttributes = ['value'];

    protected $fillable = ['key', 'type'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }
}
