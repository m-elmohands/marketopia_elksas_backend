<?php

namespace App\Filament\Resources\Content\Articles\Pages;

use App\Filament\Resources\Content\Articles\ArticleResource;
use App\Filament\Resources\Content\Concerns\HandlesTranslatedFormData;
use Filament\Resources\Pages\CreateRecord;

class CreateArticle extends CreateRecord
{
    use HandlesTranslatedFormData;

    protected static string $resource = ArticleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->extractTranslatedFormData($data);
    }

    protected function afterCreate(): void
    {
        $this->saveTranslatedFormData();
    }
}
