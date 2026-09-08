<?php

namespace App\Filament\Resources\Content\SiteSettings;

use App\Filament\Resources\Content\SiteSettings\Pages\ManageSiteSettings;
use App\Models\Content\SiteSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSiteSettings::route('/'),
        ];
    }
}
