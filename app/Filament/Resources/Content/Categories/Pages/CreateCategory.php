<?php

namespace App\Filament\Resources\Content\Categories\Pages;

use App\Filament\Resources\Content\Categories\CategoryResource;
use App\Filament\Resources\Content\Concerns\HandlesTranslatedFormData;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    use HandlesTranslatedFormData;

    protected static string $resource = CategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->extractTranslatedFormData($data);
    }

    protected function afterCreate(): void
    {
        $this->saveTranslatedFormData();
    }
}
