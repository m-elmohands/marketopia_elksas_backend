<?php

namespace App\Filament\Resources\Content\Articles\Schemas;

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

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Translations')->tabs([
                    Tab::make('العربية')->schema([TextInput::make('ar.title')->label('العنوان')->required(), Textarea::make('ar.excerpt')->label('الملخص')->required(), RichEditor::make('ar.body')->label('المحتوى')->required()]),
                    Tab::make('English')->schema([TextInput::make('en.title')->label('Title')->required(), Textarea::make('en.excerpt')->label('Excerpt')->required(), RichEditor::make('en.body')->label('Content')->required()]),
                ])->columnSpanFull(),
                Select::make('category_id')
                    ->relationship('category', 'id')
                    ->getOptionLabelFromRecordUsing(
                        fn ($record): string => $record->translateOrDefault(app()->getLocale())?->name ?? $record->slug,
                    )
                    ->required(),
                SpatieMediaLibraryFileUpload::make('cover')->collection('cover')->image()->imageEditor()->columnSpanFull(),
                TextInput::make('slug')
                    ->required(),
                Toggle::make('is_featured')
                    ->required(),
                DateTimePicker::make('published_at'),
            ]);
    }
}
