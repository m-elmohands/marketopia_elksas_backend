<?php

namespace App\Filament\Resources\Content\Motorcycles;

use App\Filament\Resources\Content\Motorcycles\Pages\CreateMotorcycle;
use App\Filament\Resources\Content\Motorcycles\Pages\EditMotorcycle;
use App\Filament\Resources\Content\Motorcycles\Pages\ListMotorcycles;
use App\Filament\Resources\Content\Motorcycles\Schemas\MotorcycleForm;
use App\Filament\Resources\Content\Motorcycles\Tables\MotorcyclesTable;
use App\Models\Content\Motorcycle;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MotorcycleResource extends Resource
{
    protected static ?string $model = Motorcycle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return MotorcycleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MotorcyclesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMotorcycles::route('/'),
            'create' => CreateMotorcycle::route('/create'),
            'edit' => EditMotorcycle::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['translations', 'category.translations']);
    }
}
