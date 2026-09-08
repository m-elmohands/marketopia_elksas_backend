<?php

namespace App\Filament\Resources\Content\Motorcycles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class MotorcycleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Translations')->tabs([
                    Tab::make('العربية')->schema([TextInput::make('ar.name')->label('الاسم')->required(), Textarea::make('ar.summary')->label('الملخص')->required(), RichEditor::make('ar.details')->label('التفاصيل')->required()]),
                    Tab::make('English')->schema([TextInput::make('en.name')->label('Name')->required(), Textarea::make('en.summary')->label('Summary')->required(), RichEditor::make('en.details')->label('Details')->required()]),
                ])->columnSpanFull(),
                Select::make('category_id')
                    ->relationship('category', 'id')
                    ->getOptionLabelFromRecordUsing(
                        fn ($record): string => $record->translateOrDefault(app()->getLocale())?->name ?? $record->slug,
                    )
                    ->required(),
                SpatieMediaLibraryFileUpload::make('gallery')->collection('gallery')->multiple()->image()->imageEditor()->reorderable()->columnSpanFull(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('manufacturer')
                    ->required(),
                TextInput::make('model_year')
                    ->required()
                    ->numeric(),
                TextInput::make('engine_cc')
                    ->numeric(),
                TextInput::make('power_hp')
                    ->numeric(),
                TextInput::make('price')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('currency')
                    ->required()
                    ->default('EGP'),
                Toggle::make('is_featured')
                    ->required(),
                DateTimePicker::make('published_at'),
            ]);
    }
}
