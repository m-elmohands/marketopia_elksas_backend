<?php

namespace App\Services\Content;

use Astrotomic\Translatable\Contracts\Translatable;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class TranslatedFormDataService
{
    /**
     * @param  Model&Translatable  $record
     * @param  array<string, mixed>  $data
     * @param  array<string>  $locales
     * @return array<string, mixed>
     */
    public function hydrate(Model $record, array $data, array $locales): array
    {
        foreach ($locales as $locale) {
            $translation = $record->translate($locale);

            $data[$locale] = collect($record->translatedAttributes)
                ->mapWithKeys(fn (string $attribute): array => [$attribute => $translation?->getAttribute($attribute)])
                ->all();
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string>  $locales
     * @return array{0: array<string, mixed>, 1: array<string, array<string, mixed>>}
     */
    public function extract(array $data, array $locales): array
    {
        $translations = [];

        foreach ($locales as $locale) {
            $translations[$locale] = is_array($data[$locale] ?? null) ? $data[$locale] : [];
            unset($data[$locale]);
        }

        return [$data, $translations];
    }

    /**
     * @param  Model&Translatable  $record
     * @param  array<string, array<string, mixed>>  $translations
     */
    public function sync(Model $record, array $translations): void
    {
        if (! property_exists($record, 'translatedAttributes')) {
            throw new InvalidArgumentException('The record must define translatedAttributes.');
        }

        foreach ($translations as $locale => $values) {
            $translation = $record->translateOrNew($locale);
            $translation->fill(collect($values)->only($record->translatedAttributes)->all());
            $record->translations()->save($translation);
        }

        $record->unsetRelation('translations');
    }
}
