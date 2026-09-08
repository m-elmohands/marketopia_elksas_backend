<?php

namespace App\Filament\Resources\Content\Categories\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Translations')->tabs([
                    Tab::make('العربية')->schema([TextInput::make('ar.name')->label('الاسم')->required(), Textarea::make('ar.description')->label('الوصف')]),
                    Tab::make('English')->schema([TextInput::make('en.name')->label('Name')->required(), Textarea::make('en.description')->label('Description')]),
                ])->columnSpanFull(),
                TextInput::make('slug')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
