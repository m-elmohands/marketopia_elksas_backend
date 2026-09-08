<?php

namespace App\Filament\Resources\Content\Concerns;

use App\Services\Content\TranslatedFormDataService;
use Astrotomic\Translatable\Contracts\Translatable;
use Illuminate\Database\Eloquent\Model;

trait HandlesTranslatedFormData
{
    /** @var array<string, array<string, mixed>> */
    protected array $pendingTranslations = [];

    /** @return array<string> */
    protected function formLocales(): array
    {
        return ['ar', 'en'];
    }

    /** @param array<string, mixed> $data */
    protected function hydrateTranslatedFormData(array $data): array
    {
        /** @var Model&Translatable $record */
        $record = $this->getRecord();

        return app(TranslatedFormDataService::class)->hydrate($record, $data, $this->formLocales());
    }

    /** @param array<string, mixed> $data */
    protected function extractTranslatedFormData(array $data): array
    {
        [$data, $this->pendingTranslations] = app(TranslatedFormDataService::class)->extract($data, $this->formLocales());

        return $data;
    }

    protected function saveTranslatedFormData(): void
    {
        /** @var Model&Translatable $record */
        $record = $this->getRecord();
        app(TranslatedFormDataService::class)->sync($record, $this->pendingTranslations);
    }
}
