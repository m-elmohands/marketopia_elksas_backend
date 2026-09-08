<?php

namespace App\Filament\Resources\Content\Articles\Pages;

use App\Filament\Resources\Content\Articles\ArticleResource;
use App\Filament\Resources\Content\Concerns\HandlesTranslatedFormData;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditArticle extends EditRecord
{
    use HandlesTranslatedFormData;

    protected static string $resource = ArticleResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->hydrateTranslatedFormData($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->extractTranslatedFormData($data);
    }

    protected function afterSave(): void
    {
        $this->saveTranslatedFormData();
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
