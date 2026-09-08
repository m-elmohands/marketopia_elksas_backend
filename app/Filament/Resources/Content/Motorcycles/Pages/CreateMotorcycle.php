<?php

namespace App\Filament\Resources\Content\Motorcycles\Pages;

use App\Filament\Resources\Content\Concerns\HandlesTranslatedFormData;
use App\Filament\Resources\Content\Motorcycles\MotorcycleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMotorcycle extends CreateRecord
{
    use HandlesTranslatedFormData;

    protected static string $resource = MotorcycleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->extractTranslatedFormData($data);
    }

    protected function afterCreate(): void
    {
        $this->saveTranslatedFormData();
    }
}
